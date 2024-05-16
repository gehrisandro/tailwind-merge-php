<?php

namespace TailwindMerge\Validators;

use TailwindMerge\Support\Str;
use TailwindMerge\Validators\Concerns\ValidatesArbitraryValue;

/**
 * @internal
 */
class ArbitraryShadowValidator implements \TailwindMerge\Contracts\ValidatorContract
{
    use ValidatesArbitraryValue;

    final public const SHADOW_REGEX = '/^(inset_)?-?((\d+)?\.?(\d+)[a-z]+|0)_-?((\d+)?\.?(\d+)[a-z]+|0)/';

    public static function validate(string $value): bool
    {
        return self::getIsArbitraryValue($value, '', self::isShadow(...));
    }

    private static function isShadow(string $value): bool
    {
        return Str::hasMatch(self::SHADOW_REGEX, $value);
    }
}
