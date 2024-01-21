<?php

namespace TailwindMerge;

use Psr\SimpleCache\CacheInterface;
use TailwindMerge\Contracts\TailwindMergeContract;
use TailwindMerge\Support\Collection;
use TailwindMerge\Support\Config;
use TailwindMerge\Support\Str;
use TailwindMerge\Support\TailwindClassParser;
use TailwindMerge\ValueObjects\ParsedClass;

class TailwindMerge implements TailwindMergeContract
{
    public static function instance(): self
    {
        return self::factory()
            ->make();
    }

    /**
     * Creates a new factory instance
     */
    public static function factory(): Factory
    {
        return new Factory();
    }

    /**
     * @param  array<string, mixed>  $configuration
     */
    public function __construct(
        private readonly array $configuration,
        private readonly ?CacheInterface $cache = null,
    ) {
    }

    /**
     * @param  string|array<array-key, string|array<array-key, string>>  ...$args
     */
    public function merge(...$args): string
    {
        $input = Collection::make($args)->flatten()->join(' ');

        return $this->withCache($input, function (string $input): string {
            $conflictingClassGroups = [];

            $parser = new TailwindClassParser($this->configuration);

            return Str::of($input)
                ->trim()
                ->split('/\s+/')
                ->map(fn (string $class): ParsedClass => $parser->parse($class)) // @phpstan-ignore-line
                ->reverse()
                ->map(function (ParsedClass $class) use (&$conflictingClassGroups): ?string {
                    $classId = $class->modifierId.$class->classGroupId;

                    if (array_key_exists($classId, $conflictingClassGroups)) {
                        return null;
                    }

                    $conflictingClassGroups[$classId] = true;

                    foreach ($this->getConflictingClassGroupIds($class->classGroupId, $class->hasPostfixModifier) as $group) {
                        $conflictingClassGroups[$class->modifierId.$group] = true;
                    }

                    return $class->originalClassName;
                })
                ->reverse()
                ->filter()
                ->join(' ');
        });
    }

    /**
     * @return array<array-key, string>
     */
    private function getConflictingClassGroupIds(string $classGroupId, bool $hasPostfixModifier): array
    {
        $conflicts = Config::getMergedConfig()['conflictingClassGroups'][$classGroupId] ?? [];

        if ($hasPostfixModifier && isset(Config::getMergedConfig()['conflictingClassGroupModifiers'][$classGroupId])) {
            return [...$conflicts, ...Config::getMergedConfig()['conflictingClassGroupModifiers'][$classGroupId]];
        }

        return $conflicts;
    }

    private function withCache(string $input, \Closure $callback): string
    {
        if (! $this->cache instanceof CacheInterface) {
            return $callback($input);
        }

        $key = hash('xxh3', 'tailwind-merge-'.$input);

        if ($this->cache->has($key)) {
            $cachedValue = $this->cache->get($key);

            if (is_string($cachedValue)) {
                return $cachedValue;
            }
        }

        $mergedClasses = $callback($input);

        $this->cache->set($key, $mergedClasses);

        return $mergedClasses;
    }
}
