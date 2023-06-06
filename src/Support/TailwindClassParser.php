<?php

namespace TailwindMerge\Support;

use Illuminate\Support\Str;
use TailwindMerge\ValueObjects\ClassPartObject;
use TailwindMerge\ValueObjects\ClassValidatorObject;
use TailwindMerge\ValueObjects\ParsedClass;

class TailwindClassParser
{
    const CLASS_PART_SEPARATOR = '-';
    const ARBITRARY_PROPERTY_REGEX = '/^\[(.+)\]$/';
    const IMPORTANT_MODIFIER = '!';

    private ClassPartObject $classMap;

    public function __construct()
    {
        $this->classMap = ClassMap::create(Config::getDefaultConfig());
    }

    /**
     * @param array<array-key, string> $classParts
     */
    private static function getGroupRecursive(array $classParts, ClassPartObject $classPartObject)
    {
        if (empty($classParts)) {
            return $classPartObject->classGroupId;
        }

        $currentClassPart = $classParts[0] ?? null;
        $nextClassPartObject = $classPartObject->nextPart[$currentClassPart] ?? null;
        $classGroupFromNextClassPart = $nextClassPartObject
            ? self::getGroupRecursive(array_slice($classParts, 1), $nextClassPartObject)
            : null;

        if ($classGroupFromNextClassPart) {
            return $classGroupFromNextClassPart;
        }

        if (empty($classPartObject->validators)) {
            return null;
        }

        $classRest = join(self::CLASS_PART_SEPARATOR, $classParts);

//        collect($classPartObject->validators)->each(fn(ClassValidatorObject $validator) => dump($classRest, $validator, ($validator->validator)($classRest)));
        return collect($classPartObject->validators)->first(fn(ClassValidatorObject $validator) => ($validator->validator)($classRest))?->classGroupId;
    }

    public function parse(string $class): ParsedClass
    {
        [$modifiers, $hasImportantModifier, $baseClassName, $maybePostfixModifierPosition] = $this->splitModifiers($class);

        $classGroupId = $this->getClassGroupId($maybePostfixModifierPosition ? Str::substr($baseClassName, 0, $maybePostfixModifierPosition) : $baseClassName);

        $hasPostfixModifier = $maybePostfixModifierPosition !== null;

        // TODO
//        if (!classGroupId) {
//            if (!maybePostfixModifierPosition) {
//                return {
//                    isTailwindClass: false as const,
//                    originalClassName,
//                        }
//                    }
//
//            classGroupId = getClassGroupId(baseClassName)
//
//                    if (!classGroupId) {
//                        return {
//                            isTailwindClass: false as const,
//                            originalClassName,
//                        }
//                    }
//
//                    hasPostfixModifier = false
//                }

        $variantModifier = join(':', $this->sortModifiers($modifiers));

        $modifierId = $hasImportantModifier ? $variantModifier . self::IMPORTANT_MODIFIER : $variantModifier;

        return new ParsedClass(
            modifiers: $modifiers,
            hasImportantModifier: $hasImportantModifier,
            hasPostfixModifier: $hasPostfixModifier,
            modifierId: $modifierId,
            classGroupId: $classGroupId,
            baseClassName: $baseClassName,
            originalClassName: $class,
        );
    }

    private function getClassGroupId(string $class): string
    {
        $classParts = explode(self::CLASS_PART_SEPARATOR, $class);

        // Classes like `-inset-1` produce an empty string as first classPart. We assume that classes for negative values are used correctly and remove it from classParts.
        if ($classParts[0] === '' && count($classParts) !== 1) {
            array_shift($classParts);
        }

        return self::getGroupRecursive($classParts, $this->classMap) ?: self::getGroupIdForArbitraryProperty($class);
    }

    private static function getGroupIdForArbitraryProperty(string $className)
    {
        if (Str::match(self::ARBITRARY_PROPERTY_REGEX, $className)) {
            $arbitraryPropertyClassName = Str::match(self::ARBITRARY_PROPERTY_REGEX, $className)[1] ?? null;
            $property = Str::before($arbitraryPropertyClassName, ':');

            if ($property) {
                // I use two dots here because one dot is used as prefix for class groups in plugins
                return 'arbitrary..' . $property;
            }
        }

        // TODO: not sure here
        return $className;
    }

    private function splitModifiers(string $className): array
    {
        $separator = ':'; // TODO: read from config
        $isSeparatorSingleCharacter = strlen($separator) === 1;
        $firstSeparatorCharacter = $separator[0];
        $separatorLength = strlen($separator);

        $modifiers = [];

        $bracketDepth = 0;
        $modifierStart = 0;
        $postfixModifierPosition = null;

        for ($index = 0; $index < strlen($className); $index++) {
            $currentCharacter = $className[$index];

            if ($bracketDepth === 0) {
                if (
                    $currentCharacter === $firstSeparatorCharacter &&
                    ($isSeparatorSingleCharacter ||
                        Str::substr($className, $index, $separatorLength) === $separator)
                ) {
                    $modifiers[] = Str::substr($className, $modifierStart, $index - $modifierStart);
                    $modifierStart = $index + $separatorLength;
                    continue;
                }

                if ($currentCharacter === '/') {
                    $postfixModifierPosition = $index;
                    continue;
                }
            }

            if ($currentCharacter === '[') {
                $bracketDepth++;
            } else if ($currentCharacter === ']') {
                $bracketDepth--;
            }
        }

        $baseClassNameWithImportantModifier =
            count($modifiers) === 0 ? $className : Str::substr($className, $modifierStart);
        $hasImportantModifier =
            Str::startsWith($baseClassNameWithImportantModifier, self::IMPORTANT_MODIFIER);
        $baseClassName = $hasImportantModifier
            ? Str::substr($baseClassNameWithImportantModifier, 1)
            : $baseClassNameWithImportantModifier;

        $maybePostfixModifierPosition = $postfixModifierPosition && $postfixModifierPosition > $modifierStart
            ? $postfixModifierPosition - $modifierStart
            : null;

        return [
            $modifiers,
            $hasImportantModifier,
            $baseClassName,
            $maybePostfixModifierPosition,
        ];
    }

    private function sortModifiers(array $modifiers): array
    {
        if (count($modifiers) <= 1) {
            return $modifiers;
        }

        $sortedModifiers = collect([]);
        $unsortedModifiers = collect([]);

        foreach ($modifiers as $modifier) {
            $isArbitraryVariant = $modifier[0] === '[';

            if ($isArbitraryVariant) {
                $sortedModifiers = $sortedModifiers->concat([...$unsortedModifiers->sort(), $modifier]);
                $unsortedModifiers = collect([]);
            } else {
                $unsortedModifiers->add($modifier);
            }
        }

        $sortedModifiers = $sortedModifiers->concat($unsortedModifiers->sort());

        return $sortedModifiers->toArray();
    }
}
