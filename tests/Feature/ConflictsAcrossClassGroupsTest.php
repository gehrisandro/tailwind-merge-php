<?php

use TailwindMerge\TailwindMerge;

it('handles conflicts across class groups correctly', function (string $input, string $output) {
    expect(TailwindMerge::merge($input))
        ->toBe($output);
})->with([
    ['inset-1 inset-x-1', 'inset-1 inset-x-1'],
    ['inset-x-1 inset-1', 'inset-1'],
    ['inset-x-1 left-1 inset-1', 'inset-1'],
    ['inset-x-1 inset-1 left-1', 'inset-1 left-1'],
    ['inset-x-1 right-1 inset-1', 'inset-1'],
    ['inset-x-1 right-1 inset-x-1', 'inset-x-1'],
    ['inset-x-1 right-1 inset-y-1', 'inset-x-1 right-1 inset-y-1'],
    ['right-1 inset-x-1 inset-y-1', 'inset-x-1 inset-y-1'],
    ['inset-x-1 hover:left-1 inset-1', 'hover:left-1 inset-1'],
]);

it('ring and shadow classes do not create conflict', function (string $input, string $output) {
    expect(TailwindMerge::merge($input))
        ->toBe($output);
})->with([
    ['ring shadow', 'ring shadow'],
    ['ring-2 shadow-md', 'ring-2 shadow-md'],
    ['shadow ring', 'shadow ring'],
    ['shadow-md ring-2', 'shadow-md ring-2'],
]);
