<?php

namespace TailwindMerge\Validators;

use TailwindMerge\Contracts\ValidatorContract;
use TailwindMerge\Support\Str;

/**
 * @internal
 */
class PercentValidator implements ValidatorContract
{
    public static function validate(string $value): bool
    {
        if (! Str::endsWith($value, '%')) {
            return false;
        }

        return NumberValidator::validate(Str::of($value)->substr(0, -1)->toString());
    }
}
