<?php

use TailwindMerge\TailwindMerge;

test('handles conflicts across class groups correctly', function() {
    expect(TailwindMerge::merge('inset-1 inset-x-1'))->toBe('inset-1 inset-x-1');
    expect(TailwindMerge::merge('inset-x-1 inset-1'))->toBe('inset-1');
    expect(TailwindMerge::merge('inset-x-1 left-1 inset-1'))->toBe('inset-1');
    expect(TailwindMerge::merge('inset-x-1 inset-1 left-1'))->toBe('inset-1 left-1');
    expect(TailwindMerge::merge('inset-x-1 right-1 inset-1'))->toBe('inset-1');
    expect(TailwindMerge::merge('inset-x-1 right-1 inset-x-1'))->toBe('inset-x-1');
    expect(TailwindMerge::merge('inset-x-1 right-1 inset-y-1'))->toBe('inset-x-1 right-1 inset-y-1');
    expect(TailwindMerge::merge('right-1 inset-x-1 inset-y-1'))->toBe('inset-x-1 inset-y-1');
    expect(TailwindMerge::merge('inset-x-1 hover:left-1 inset-1'))->toBe('hover:left-1 inset-1');
});

test('ring and shadow classes do not create conflict', function() {
    expect(TailwindMerge::merge('ring shadow'))->toBe('ring shadow');
    expect(TailwindMerge::merge('ring-2 shadow-md'))->toBe('ring-2 shadow-md');
    expect(TailwindMerge::merge('shadow ring'))->toBe('shadow ring');
    expect(TailwindMerge::merge('shadow-md ring-2'))->toBe('shadow-md ring-2');
});
