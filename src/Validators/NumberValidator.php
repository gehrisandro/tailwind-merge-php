<?php

namespace TailwindMerge\Validators;

/**
 * @internal
 */
class NumberValidator implements \TailwindMerge\Contracts\ValidatorContract
{
    public static function validate(string $value): bool
    {
        return is_numeric($value);
    }
}
