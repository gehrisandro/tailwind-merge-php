<?php

namespace TailwindMerge\Support;

use TailwindMerge\ValueObjects\ClassPartObject;
use TailwindMerge\ValueObjects\ClassValidatorObject;
use TailwindMerge\ValueObjects\ThemeGetter;

class ClassMap
{
    const CLASS_PART_SEPARATOR = '-';

    public static function create(array $config)
    {
        $theme = $config['theme'];
        $prefix = $config['prefix'] ?? null;

        $classMap = new ClassPartObject();

        $prefixedClassGroupEntries = self::getPrefixedClassGroupEntries(
            $config['classGroups'],
            $prefix,
        );

        foreach ($prefixedClassGroupEntries as $classGroupId => $classGroup) {
            self::processClassesRecursively($classGroup, $classMap, $classGroupId, $theme);
        }

        return $classMap;
    }

    private static function getPrefixedClassGroupEntries(array $classGroupEntries, ?string $prefix): array
    {
        if (!$prefix) {
            return $classGroupEntries;
        }

        return collect($classGroupEntries)->map(function ($classGroupEntry) use ($prefix) {
                $prefixedClassGroup = collect($classGroupEntry[1])->map(function (string $classDefinition) use ($prefix) {
                        if (is_string($classDefinition)) {
                            return $prefix . $classDefinition;
                        }

                        // TODO
//            if (typeof classDefinition === 'object') {
//            return Object.fromEntries(
//                    Object.entries(classDefinition).map(([key, value]) => [prefix + key, value]),
//                )
//            }

                        return $classDefinition;
                    });

                return [$classGroupEntry[0], $prefixedClassGroup];
            });
    }

    public static function processClassesRecursively(array $classGroup, ClassPartObject $classPartObject, string $classGroupId, array $theme)
    {
        foreach ($classGroup as $classDefinition) {
            if (is_string($classDefinition)) {
                $classPartObjectToEdit = $classDefinition === '' ? $classPartObject : self::getPart($classPartObject, $classDefinition);
                $classPartObjectToEdit->classGroupId = $classGroupId;
                continue;
            }

            if (self::isThemeGetter($classDefinition)) {
                self::processClassesRecursively(
                    $classDefinition->get($theme),
                    $classPartObject,
                    $classGroupId,
                    $theme,
                );
                continue;
            }

            if (is_callable($classDefinition)) {
                $classPartObject->validators[] = new ClassValidatorObject(
                    classGroupId: $classGroupId,
                    validator: $classDefinition,
                );

                continue;
            }

            foreach ($classDefinition as $key => $classGroup) {
                self::processClassesRecursively(
                    $classGroup,
                    self::getPart($classPartObject, $key),
                    $classGroupId,
                    $theme,
                );
            }
        }
    }

    private static function isThemeGetter($classDefinition): bool
    {
        return $classDefinition instanceof ThemeGetter;
    }

    private static function getPart(ClassPartObject $classPartObject, string $path): ClassPartObject
    {
        $currentClassPartObject = $classPartObject;

        foreach (explode(self::CLASS_PART_SEPARATOR, $path) as $pathPart) {
            if (!isset($currentClassPartObject->nextPart[$pathPart])) {
                $currentClassPartObject->nextPart[$pathPart] = new ClassPartObject();
            }

            $currentClassPartObject = $currentClassPartObject->nextPart[$pathPart];
        }

        return $currentClassPartObject;
    }
}
