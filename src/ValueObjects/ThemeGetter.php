<?php

namespace TailwindMerge\ValueObjects;

class ThemeGetter
{
    public function __construct(
        public string $key
    )
    {
    }

    public function get(array $theme)
    {
        return $theme[$this->key] ?? [];
    }
}
