<?php

namespace TailwindMerge\Support;

use TailwindMerge\ValueObjects\ClassPartObject;
use TailwindMerge\ValueObjects\ClassValidatorObject;
use TailwindMerge\ValueObjects\ParsedClass;

class TailwindClassParser
{
    final public const CLASS_PART_SEPARATOR = '-';

    final public const ARBITRARY_PROPERTY_REGEX = '/^\[(.+)\]$/';

    final public const IMPORTANT_MODIFIER = '!';

    private readonly ClassPartObject $classMap;

    /**
     * @param  array{cacheSize: int, prefix: ?string, theme: array<string, mixed>, classGroups: array<string, mixed>,conflictingClassGroups: array<string, array<int, string>>, conflictingClassGroupModifiers: array<string, array<int, string>>}  $config
     */
    public function __construct(array $configuration)
    {
        $this->classMap = ClassMap::create($configuration);
    }

    /**
     * @param  array<array-key, string>  $classParts
     */
    private static function getGroupRecursive(array $classParts, ClassPartObject $classPartObject): ?string
    {
        if ($classParts === []) {
            return $classPartObject->classGroupId;
        }

        $currentClassPart = $classParts[0] ?? null;
        $nextClassPartObject = $classPartObject->nextPart[$currentClassPart] ?? null;
        $classGroupFromNextClassPart = $nextClassPartObject !== null
            ? self::getGroupRecursive(array_slice($classParts, 1), $nextClassPartObject)
            : null;

        if ($classGroupFromNextClassPart) {
            return $classGroupFromNextClassPart;
        }

        if ($classPartObject->validators === []) {
            return null;
        }

        $classRest = implode(self::CLASS_PART_SEPARATOR, $classParts);

        return Collection::make($classPartObject->validators)->first(fn (ClassValidatorObject $validator) => ($validator->validator)($classRest))?->classGroupId;
    }

    public function parse(string $class): ParsedClass
    {
        [
            'modifiers' => $modifiers,
            'hasImportantModifier' => $hasImportantModifier,
            'baseClassName' => $baseClassName,
            'maybePostfixModifierPosition' => $maybePostfixModifierPosition
        ] = $this->splitModifiers($class);

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

        $variantModifier = implode(':', $this->sortModifiers($modifiers));

        $modifierId = $hasImportantModifier ? $variantModifier.self::IMPORTANT_MODIFIER : $variantModifier;

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

        return self::getGroupRecursive($classParts, $this->classMap) ?: $this->getGroupIdForArbitraryProperty($class);
    }

    private function getGroupIdForArbitraryProperty(string $className): string
    {
        if (Str::match(self::ARBITRARY_PROPERTY_REGEX, $className) !== '' && Str::match(self::ARBITRARY_PROPERTY_REGEX, $className) !== '0') {
            $arbitraryPropertyClassName = Str::match(self::ARBITRARY_PROPERTY_REGEX, $className);
            $property = Str::before($arbitraryPropertyClassName, ':');

            if ($property !== '' && $property !== '0') {
                // I use two dots here because one dot is used as prefix for class groups in plugins
                return 'arbitrary..'.$property;
            }
        }

        // TODO: not sure here
        return $className;
    }

    /**
     * @return array{modifiers: array<array-key, string>, hasImportantModifier: bool, baseClassName: string, maybePostfixModifierPosition: int|null}
     */
    private function splitModifiers(string $className): array
    {
        $separator = isset(Config::getMergedConfig()['separator']) && is_string(Config::getMergedConfig()['separator']) ? Config::getMergedConfig()['separator'] : ':';
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
            } elseif ($currentCharacter === ']') {
                $bracketDepth--;
            }
        }

        $baseClassNameWithImportantModifier =
            $modifiers === [] ? $className : Str::substr($className, $modifierStart);
        $hasImportantModifier =
            Str::startsWith($baseClassNameWithImportantModifier, self::IMPORTANT_MODIFIER);
        $baseClassName = $hasImportantModifier
            ? Str::substr($baseClassNameWithImportantModifier, 1)
            : $baseClassNameWithImportantModifier;

        $maybePostfixModifierPosition = $postfixModifierPosition && $postfixModifierPosition > $modifierStart
            ? $postfixModifierPosition - $modifierStart
            : null;

        return [
            'modifiers' => $modifiers,
            'hasImportantModifier' => $hasImportantModifier,
            'baseClassName' => $baseClassName,
            'maybePostfixModifierPosition' => $maybePostfixModifierPosition,
        ];
    }

    /**
     * @param  array<array-key, string>  $modifiers
     * @return array<array-key, string>
     */
    private function sortModifiers(array $modifiers): array
    {
        if (count($modifiers) <= 1) {
            return $modifiers;
        }

        /**
         * @var Collection<array-key, string> $sortedModifiers
         */
        $sortedModifiers = Collection::make();
        $unsortedModifiers = Collection::make();

        foreach ($modifiers as $modifier) {
            $isArbitraryVariant = $modifier[0] === '[';

            if ($isArbitraryVariant) {
                $sortedModifiers = $sortedModifiers->concat([...$unsortedModifiers->sort()->all(), $modifier]);
                $unsortedModifiers = Collection::make();
            } else {
                $unsortedModifiers->add($modifier);
            }
        }

        $sortedModifiers = $sortedModifiers->concat($unsortedModifiers->sort());

        return $sortedModifiers->all();
    }
}
