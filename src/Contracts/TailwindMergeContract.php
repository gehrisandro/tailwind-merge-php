<?php

namespace TailwindMerge\Contracts;

interface TailwindMergeContract
{
    /**
     * @param  array<array-key, string|array<array-key, string>>  ...$args
     */
    public function merge(...$args): string;
}
