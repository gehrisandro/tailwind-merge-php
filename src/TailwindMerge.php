<?php

namespace TailwindMerge;

use TailwindMerge\Contracts\TailwindMergeContract;
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
        private readonly array $configuration
    ) {
    }

    /**
     * @param  array<array-key, string|array<array-key, string>>  ...$args
     */
    public function merge(...$args): string
    {
        $input = collect($args)->flatten()->join(' ');

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

                foreach (self::getConflictingClassGroupIds($class->classGroupId, $class->hasPostfixModifier) as $group) {
                    $conflictingClassGroups[$class->modifierId.$group] = true;
                }

                return $class->originalClassName;
            })
            ->reverse()
            ->filter()
            ->join(' ');
    }

    /**
     * @return array<array-key, string>
     */
    private static function getConflictingClassGroupIds(string $classGroupId, bool $hasPostfixModifier): array
    {
        $conflicts = Config::getMergedConfig()['conflictingClassGroups'][$classGroupId] ?? [];

        if ($hasPostfixModifier && isset(Config::getMergedConfig()['conflictingClassGroupModifiers'][$classGroupId])) {
            return [...$conflicts, ...Config::getMergedConfig()['conflictingClassGroupModifiers'][$classGroupId]];
        }

        return $conflicts;
    }
}
