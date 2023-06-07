<?php

namespace TailwindMerge\ValueObjects;

class ParsedClass
{
    /**
     * @param  array<array-key, string>  $modifiers
     */
    public function __construct(
        public array $modifiers,
        public bool $hasImportantModifier,
        public bool $hasPostfixModifier,
        public string $modifierId,
        public string $classGroupId,
        public string $baseClassName,
        public string $originalClassName,
    ) {
        //
    }
}
