<?php

use TailwindMerge\TailwindMerge;

test('merges classes from same group correctly', function() {
    expect(TailwindMerge::merge('overflow-x-auto overflow-x-hidden'))->toBe('overflow-x-hidden');
    expect(TailwindMerge::merge('w-full w-fit'))->toBe('w-fit');
    expect(TailwindMerge::merge('overflow-x-auto overflow-x-hidden overflow-x-scroll'))->toBe('overflow-x-scroll');
    expect(TailwindMerge::merge('overflow-x-auto hover:overflow-x-hidden overflow-x-scroll'))->toBe(
        'hover:overflow-x-hidden overflow-x-scroll',
    );
    expect(
        TailwindMerge::merge('overflow-x-auto hover:overflow-x-hidden hover:overflow-x-auto overflow-x-scroll'),
    )->toBe('hover:overflow-x-auto overflow-x-scroll');
});

test('merges classes from Font Variant Numeric section correctly', function() {
    expect(TailwindMerge::merge('lining-nums tabular-nums diagonal-fractions'))->toBe(
        'lining-nums tabular-nums diagonal-fractions',
    );
    expect(TailwindMerge::merge('normal-nums tabular-nums diagonal-fractions'))->toBe(
        'tabular-nums diagonal-fractions',
    );
    expect(TailwindMerge::merge('tabular-nums diagonal-fractions normal-nums'))->toBe('normal-nums');
    expect(TailwindMerge::merge('tabular-nums proportional-nums'))->toBe('proportional-nums');
});
