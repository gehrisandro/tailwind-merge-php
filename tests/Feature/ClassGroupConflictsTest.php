<?php

use TailwindMerge\TailwindMerge;

it('merges classes from same group correctly', function (string $input, string $output) {
    expect(TailwindMerge::instance()->merge($input))
        ->toBe($output);
})->with([
    ['overflow-x-auto overflow-x-hidden', 'overflow-x-hidden'],
    ['w-full w-fit', 'w-fit'],
    ['overflow-x-auto overflow-x-hidden overflow-x-scroll', 'overflow-x-scroll'],
    ['overflow-x-auto hover:overflow-x-hidden overflow-x-scroll', 'hover:overflow-x-hidden overflow-x-scroll'],
    ['overflow-x-auto hover:overflow-x-hidden hover:overflow-x-auto overflow-x-scroll', 'hover:overflow-x-auto overflow-x-scroll'],
]);

it('merges classes from Font Variant Numeric section correctly', function (string $input, string $output) {
    expect(TailwindMerge::instance()->merge($input))
        ->toBe($output);
})->with([
    ['lining-nums tabular-nums diagonal-fractions', 'lining-nums tabular-nums diagonal-fractions'],
    ['normal-nums tabular-nums diagonal-fractions', 'tabular-nums diagonal-fractions'],
    ['tabular-nums diagonal-fractions normal-nums', 'normal-nums'],
    ['tabular-nums proportional-nums', 'proportional-nums'],
]);
