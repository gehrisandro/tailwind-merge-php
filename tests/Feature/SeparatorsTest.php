<?php

use TailwindMerge\TailwindMerge;

test('single character separator working correctly', function () {
    //    const twMerge = extendTailwindMerge({
    //        separator: '_',
    //    })

    expect(TailwindMerge::instance()->merge('block hidden'))->toBe('hidden');

    expect(TailwindMerge::instance()->merge('p-3 p-2'))->toBe('p-2');

    expect(TailwindMerge::instance()->merge('!right-0 !inset-0'))->toBe('!inset-0');

    expect(TailwindMerge::instance()->merge('hover_focus_!right-0 focus_hover_!inset-0'))->toBe('focus_hover_!inset-0');
    expect(TailwindMerge::instance()->merge('hover:focus:!right-0 focus:hover:!inset-0'))->toBe(
        'hover:focus:!right-0 focus:hover:!inset-0',
    );
})->todo();

test('multiple character separator working correctly', function () {
    //    const twMerge = extendTailwindMerge({
    //        separator: '__',
    //    })

    expect(TailwindMerge::instance()->merge('block hidden'))->toBe('hidden');

    expect(TailwindMerge::instance()->merge('p-3 p-2'))->toBe('p-2');

    expect(TailwindMerge::instance()->merge('!right-0 !inset-0'))->toBe('!inset-0');

    expect(TailwindMerge::instance()->merge('hover__focus__!right-0 focus__hover__!inset-0'))->toBe('focus__hover__!inset-0');
    expect(TailwindMerge::instance()->merge('hover:focus:!right-0 focus:hover:!inset-0'))->toBe(
        'hover:focus:!right-0 focus:hover:!inset-0',
    );
})->todo();
