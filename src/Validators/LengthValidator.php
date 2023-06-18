<?php

namespace TailwindMerge\Validators;

use Illuminate\Support\Collection;
use TailwindMerge\Support\Str;

/**
 * @internal
 */
class LengthValidator implements \TailwindMerge\Contracts\ValidatorContract
{
    final const FRACTION_REGEX = '/^\d+\/\d+$/';

    public static function validate(string $value): bool
    {
        if (NumberValidator::validate($value)) {
            return true;
        }
        if (self::stringLengths()->contains($value)) {
            return true;
        }
        if (Str::hasMatch(self::FRACTION_REGEX, $value)) {
            return true;
        }

        return ArbitraryLengthValidator::validate($value);
    }

    /**
     * @return Collection<int, string>
     */
    private static function stringLengths(): Collection
    {
        return collect(['px', 'full', 'screen']);
    }
}
