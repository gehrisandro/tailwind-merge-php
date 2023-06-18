<?php

namespace TailwindMerge\Validators\Concerns;

/**
 * @internal
 */
trait ValidatesArbitraryValue
{
    protected static function getIsArbitraryValue(string $value, string $label, callable $isLengthOnly): bool
    {
        preg_match('/^\[(?:([a-z-]+):)?(.+)\]$/i', $value, $result);

        if ($result !== []) {
            if ($result[1] !== '' && $result[1] !== '0') {
                return $result[1] === $label;
            }

            return $isLengthOnly($result[2] ?? null);
        }

        return false;
    }
}
