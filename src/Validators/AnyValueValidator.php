<?php

namespace TailwindMerge\Validators;

/**
 * @internal
 */
class AnyValueValidator implements \TailwindMerge\Contracts\ValidatorContract
{
    public static function validate(string $value): bool
    {
        return true;
    }
}
