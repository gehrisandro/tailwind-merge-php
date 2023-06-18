<?php

namespace TailwindMerge\Validators;

use TailwindMerge\Support\Str;

/**
 * @internal
 */
class PercentValidator implements \TailwindMerge\Contracts\ValidatorContract
{
    public static function validate(string $value): bool
    {
        if (! Str::endsWith($value, '%')) {
            return false;
        }

        return NumberValidator::validate(Str::of($value)->substr(0, -1)->toString());
    }
}
