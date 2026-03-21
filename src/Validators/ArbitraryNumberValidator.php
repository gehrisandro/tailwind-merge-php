<?php

namespace TailwindMerge\Validators;

use TailwindMerge\Contracts\ValidatorContract;
use TailwindMerge\Validators\Concerns\ValidatesArbitraryValue;

/**
 * @internal
 */
class ArbitraryNumberValidator implements ValidatorContract
{
    use ValidatesArbitraryValue;

    public static function validate(string $value): bool
    {
        return self::getIsArbitraryValue($value, 'number', NumberValidator::validate(...));
    }
}
