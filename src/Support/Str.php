<?php

namespace TailwindMerge\Support;

class Str
{
    public static function hasMatch(string $pattern, string $value): bool
    {
        return preg_match($pattern, $value) === 1;
    }

    public static function of(string $string): Stringable
    {
        return new Stringable($string);
    }

    public static function startsWith(string $haystack, string $needle): bool
    {
        return str_starts_with($haystack, $needle);
    }

    public static function match(string $pattern, string $subject): string
    {
        preg_match($pattern, $subject, $matches);

        if ($matches === []) {
            return '';
        }

        return $matches[1] ?? $matches[0];
    }

    public static function before(string $subject, string $search): string
    {
        if ($search === '') {
            return $subject;
        }

        $result = strstr($subject, $search, true);

        return $result === false ? $subject : $result;
    }

    public static function substr(string $string, int $start, ?int $length = null, string $encoding = 'UTF-8'): string
    {
        return mb_substr($string, $start, $length, $encoding);
    }

    public static function endsWith(string $haystack, string $needle): bool
    {
        return str_ends_with($haystack, $needle);
    }
}
