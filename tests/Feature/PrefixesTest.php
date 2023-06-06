<?php

use TailwindMerge\TailwindMerge;

test('prefix working correctly', function() {
//    const twMerge = extendTailwindMerge({
//        prefix: 'tw-',
//    })

    expect(TailwindMerge::merge('tw-block tw-hidden'))->toBe('tw-hidden');
    expect(TailwindMerge::merge('block hidden'))->toBe('block hidden');

    expect(TailwindMerge::merge('tw-p-3 tw-p-2'))->toBe('tw-p-2');
    expect(TailwindMerge::merge('p-3 p-2'))->toBe('p-3 p-2');

    expect(TailwindMerge::merge('!tw-right-0 !tw-inset-0'))->toBe('!tw-inset-0');

    expect(TailwindMerge::merge('hover:focus:!tw-right-0 focus:hover:!tw-inset-0'))->toBe(
        'focus:hover:!tw-inset-0',
    );
})->todo();
