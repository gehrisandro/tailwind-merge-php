<?php

namespace TailwindMerge\Support;

class Str extends \Illuminate\Support\Str
{
    public static function hasMatch(string $pattern, string $value): bool
    {
        return preg_match($pattern, $value) === 1;
    }
}
