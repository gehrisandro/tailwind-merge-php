<?php

namespace TailwindMerge\ValueObjects;

class ThemeGetter
{
    public function __construct(
        public string $key
    ) {
    }

    /**
     * @param  array<string, array<string, mixed>>  $theme
     * @return array<string, mixed>
     */
    public function get(array $theme): array
    {
        return $theme[$this->key] ?? [];
    }
}
