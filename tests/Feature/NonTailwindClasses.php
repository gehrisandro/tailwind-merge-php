<?php

use TailwindMerge\TailwindMerge;

it('does not alter non-tailwind classes', function (string $input, string $output) {
    expect(TailwindMerge::merge($input))
        ->toBe($output);
})->with([
    ['non-tailwind-class inline block', 'non-tailwind-class block'],
    ['inline block inline-1', 'block inline-1'],
    ['inline block i-inline', 'block i-inline'],
    ['focus:inline focus:block focus:inline-1', 'focus:block focus:inline-1'],
]);
