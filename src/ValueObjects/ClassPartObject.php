<?php

namespace TailwindMerge\ValueObjects;

class ClassPartObject
{
    /**
     * @param  array<array-key, ClassPartObject>  $nextPart
     * @param  array<array-key, ClassValidatorObject>  $validators
     */
    public function __construct(
        public array $nextPart = [],
        public array $validators = [],
        public ?string $classGroupId = null,
    ) {
    }
}
