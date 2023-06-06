<?php

namespace TailwindMerge;

use Illuminate\Support\Str;
use TailwindMerge\Support\Config;
use TailwindMerge\Support\TailwindClassParser;
use TailwindMerge\ValueObjects\ParsedClass;

class TailwindMerge
{
    public static function merge(...$args): string
    {
        $input = collect($args)->flatten()->join(' ');

        $conflictingClassGroups = [];

        $parser = new TailwindClassParser();

        return Str::of($input)
            ->trim()
            ->split('/\s+/')
            ->map(fn (string $class): \TailwindMerge\ValueObjects\ParsedClass => $parser->parse($class))
//            ->dd()
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
//            ->dd()
            ->filter()
            ->join(' ');
    }

    private static function getConflictingClassGroupIds(string $classGroupId, bool $hasPostfixModifier)
    {
        $conflicts = Config::getDefaultConfig()['conflictingClassGroups'][$classGroupId] ?? [];

        if ($hasPostfixModifier && isset(Config::getDefaultConfig()['conflictingClassGroupModifiers'][$classGroupId])) {
            return [...$conflicts, ...Config::getDefaultConfig()['conflictingClassGroupModifiers'][$classGroupId]];
        }

        return $conflicts;
    }
}
