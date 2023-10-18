<?php

namespace TailwindMerge\Validators\Concerns;

/**
 * @internal
 */
trait ValidatesArbitraryValue
{
    /**
     * @param  string|array<array-key, string>  $labels
     */
    protected static function getIsArbitraryValue(string $value, string|array $labels, callable $isLengthOnly): bool
    {
        $labels = is_string($labels) ? [$labels] : $labels;

        preg_match('/^\[(?:([a-z-]+):)?(.+)\]$/i', $value, $result);

        if ($result !== []) {
            if ($result[1] !== '' && $result[1] !== '0') {
                return in_array($result[1], $labels);
            }

            return $isLengthOnly($result[2] ?? null);
        }

        return false;
    }
}
