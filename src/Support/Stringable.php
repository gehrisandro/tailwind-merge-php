<?php

namespace TailwindMerge\Support;

class Stringable
{
    public function __construct(protected string $value)
    {
    }

    public function trim(string $characters = ' '): self
    {
        return new self(trim($this->value, $characters));
    }

    /**
     * @return Collection<int, string>
     */
    public function split(string $pattern, int $limit = -1, int $flags = 0): Collection
    {
        $segments = preg_split($pattern, $this->value, $limit, $flags);

        return $segments === [] || $segments === false ? Collection::make() : Collection::make($segments);
    }

    public function substr(int $start, ?int $length = null, string $encoding = 'UTF-8'): self
    {
        return new self(Str::substr($this->value, $start, $length, $encoding));
    }

    public function toString(): string
    {
        return $this->value;
    }
}
