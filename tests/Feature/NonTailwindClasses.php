<?php

use TailwindMerge\TailwindMerge;

test('does not alter non-tailwind classes', function () {
    expect(TailwindMerge::merge('non-tailwind-class inline block'))->toBe('non-tailwind-class block');
    expect(TailwindMerge::merge('inline block inline-1'))->toBe('block inline-1');
    expect(TailwindMerge::merge('inline block i-inline'))->toBe('block i-inline');
    expect(TailwindMerge::merge('focus:inline focus:block focus:inline-1'))->toBe('focus:block focus:inline-1');
});
