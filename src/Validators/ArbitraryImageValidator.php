<?php

namespace TailwindMerge\Validators;

use TailwindMerge\Contracts\ValidatorContract;
use TailwindMerge\Support\Str;
use TailwindMerge\Validators\Concerns\ValidatesArbitraryValue;

/**
 * @internal
 */
class ArbitraryImageValidator implements ValidatorContract
{
    use ValidatesArbitraryValue;

    final public const IMAGE_REGEX = '/^(url|image|image-set|cross-fade|element|(repeating-)?(linear|radial|conic)-gradient)\(.+\)$/';

    public static function validate(string $value): bool
    {
        return self::getIsArbitraryValue($value, ['image', 'url'], self::isImage(...));
    }

    private static function isImage(string $value): bool
    {
        return Str::hasMatch(self::IMAGE_REGEX, $value);
    }
}
