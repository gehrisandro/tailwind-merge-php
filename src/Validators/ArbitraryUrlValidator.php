<?php

namespace TailwindMerge\Validators;

use TailwindMerge\Support\Str;
use TailwindMerge\Validators\Concerns\ValidatesArbitraryValue;

/**
 * @internal
 */
class ArbitraryUrlValidator implements \TailwindMerge\Contracts\ValidatorContract
{
    use ValidatesArbitraryValue;

    public static function validate(string $value): bool
    {
        return self::getIsArbitraryValue($value, 'url', self::isUrl(...));
    }

    private static function isUrl(string $value): bool
    {
        return Str::startsWith($value, 'url(');
    }
}
