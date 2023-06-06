<?php

use TailwindMerge\TailwindMerge;

it('does basic merges', function (string $input, string $output) {
    expect(TailwindMerge::merge($input))
        ->toBe($output);
})->with([
    ['h-10 w-10', 'h-10 w-10'],
    ['mix-blend-normal mix-blend-multiply', 'mix-blend-multiply'],
    ['h-10 h-min', 'h-min'],
    ['stroke-black stroke-1', 'stroke-black stroke-1'],
    ['stroke-2 stroke-[3]', 'stroke-[3]'],
    ['outline-black outline-1', 'outline-black outline-1'],
    ['grayscale-0 grayscale-[50%]', 'grayscale-[50%]'],
    ['grow grow-[2]', 'grow-[2]'],
]);

it('does basic merges with multiple parameters', function () {
    expect(TailwindMerge::merge('grow', [null, false, [['grow-[2]']]]))
        ->toBe('grow-[2]');
});
