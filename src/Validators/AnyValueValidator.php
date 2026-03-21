<?php

namespace TailwindMerge\Validators;

use TailwindMerge\Contracts\ValidatorContract;

/**
 * @internal
 */
class AnyValueValidator implements ValidatorContract
{
    public static function validate(string $value): bool
    {
        return true;
    }
}
