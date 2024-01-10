<?php

namespace TailwindMerge\Validators;

use TailwindMerge\Support\Collection;
use TailwindMerge\Support\Str;

/**
 * @internal
 */
class LengthValidator implements \TailwindMerge\Contracts\ValidatorContract
{
    final public const FRACTION_REGEX = '/^\d+\/\d+$/';

    public static function validate(string $value): bool
    {
        if (NumberValidator::validate($value)) {
            return true;
        }
        if (self::stringLengths()->contains($value)) {
            return true;
        }

        return Str::hasMatch(self::FRACTION_REGEX, $value);
    }

    /**
     * @return Collection<int, string>
     */
    private static function stringLengths(): Collection
    {
        return Collection::make(['px', 'full', 'screen']);
    }
}
