<?php

namespace TailwindMerge\Validators;

use TailwindMerge\Contracts\ValidatorContract;

/**
 * @internal
 */
class NumberValidator implements ValidatorContract
{
    public static function validate(string $value): bool
    {
        return is_numeric($value);
    }
}
