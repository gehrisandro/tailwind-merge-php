<?php

namespace TailwindMerge\Contracts;

use TailwindMerge\TailwindMerge;

interface TailwindMergeContract
{
    /**
     * @param  array<array-key, string|array<array-key, string>>  ...$args
     */
    public function merge(...$args): string;
}
