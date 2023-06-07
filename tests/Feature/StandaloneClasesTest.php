<?php

use TailwindMerge\TailwindMerge;

it('merges standalone classes from same group correctly', function (string $input, string $output) {
    expect(TailwindMerge::merge($input))
        ->toBe($output);
})->with([
    ['inline block', 'block'],
    ['hover:block hover:inline', 'hover:inline'],
    ['hover:block hover:block', 'hover:block'],
    ['inline hover:inline focus:inline hover:block hover:focus:block', 'inline focus:inline hover:block hover:focus:block'],
    ['underline line-through', 'line-through'],
    ['line-through no-underline', 'no-underline'],
]);
