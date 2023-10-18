<?php

namespace TailwindMerge\Validators;

use TailwindMerge\Validators\Concerns\ValidatesArbitraryValue;

/**
 * @internal
 */
class IntegerValidator implements \TailwindMerge\Contracts\ValidatorContract
{
    use ValidatesArbitraryValue;

    public static function validate(string $value): bool
    {
        return self::isIntegerOnly($value);
    }

    private static function isIntegerOnly(string $value): bool
    {
        return (string) (int) $value === $value;
    }
}
