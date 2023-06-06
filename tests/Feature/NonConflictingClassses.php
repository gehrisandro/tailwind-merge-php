<?php

use TailwindMerge\TailwindMerge;

test('merges non-conflicting classes correctly', function () {
    expect(TailwindMerge::merge('border-t border-white/10'))->toBe('border-t border-white/10');
    expect(TailwindMerge::merge('border-t border-white'))->toBe('border-t border-white');
    expect(TailwindMerge::merge('text-3.5xl text-black'))->toBe('text-3.5xl text-black');
});
