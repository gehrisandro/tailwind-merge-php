<?php

use TailwindMerge\TailwindMerge;

test('handles negative value conflicts correctly', function() {
    expect(TailwindMerge::merge('-m-2 -m-5'))->toBe('-m-5');
    expect(TailwindMerge::merge('-top-12 -top-2000'))->toBe('-top-2000');
});

test('handles conflicts between positive and negative values correctly', function() {
    expect(TailwindMerge::merge('-m-2 m-auto'))->toBe('m-auto');
    expect(TailwindMerge::merge('top-12 -top-69'))->toBe('-top-69');
});

test('handles conflicts across groups with negative values correctly', function() {
    expect(TailwindMerge::merge('-right-1 inset-x-1'))->toBe('inset-x-1');
    expect(TailwindMerge::merge('hover:focus:-right-1 focus:hover:inset-x-1'))->toBe('focus:hover:inset-x-1');
});
