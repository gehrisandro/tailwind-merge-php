<?php

namespace TailwindMerge\Validators;

use TailwindMerge\Contracts\ValidatorContract;
use TailwindMerge\Support\Str;

/**
 * @internal
 */
class ArbitraryValueValidator implements ValidatorContract
{
    final public const ARBITRARY_VALUE_REGEX = '/^\[(?:([a-z-]+):)?(.+)\]$/i';

    public static function validate(string $value): bool
    {
        return Str::hasMatch(self::ARBITRARY_VALUE_REGEX, $value);
    }
}
