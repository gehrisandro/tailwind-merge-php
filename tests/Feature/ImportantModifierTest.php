<?php

use TailwindMerge\TailwindMerge;

test('merges tailwind classes with important modifier correctly', function () {
    expect(TailwindMerge::merge('!font-medium !font-bold'))->toBe('!font-bold');
    expect(TailwindMerge::merge('!font-medium !font-bold font-thin'))->toBe('!font-bold font-thin');
    expect(TailwindMerge::merge('!right-2 !-inset-x-px'))->toBe('!-inset-x-px');
    expect(TailwindMerge::merge('focus:!inline focus:!block'))->toBe('focus:!block');
});
