<?php

use TailwindMerge\TailwindMerge;

it('merges non-conflicting classes correctly', function (string $input, string $output) {
    expect(TailwindMerge::merge($input))
        ->toBe($output);
})->with([
    ['border-t border-white/10', 'border-t border-white/10'],
    ['border-t border-white', 'border-t border-white'],
    ['text-3.5xl text-black', 'text-3.5xl text-black'],
]);
