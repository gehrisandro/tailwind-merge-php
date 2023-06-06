<?php

use TailwindMerge\TailwindMerge;

test('theme scale can be extended', function() {
//    const tailwindMerge = extendTailwindMerge({
//        theme: {
//        spacing: ['my-space'],
//            margin: ['my-margin'],
//        },
//    })

    expect(TailwindMerge::merge('p-3 p-my-space p-my-margin'))->toBe('p-my-space p-my-margin');
    expect(TailwindMerge::merge('m-3 m-my-space m-my-margin'))->toBe('m-my-margin');
})->todo();

test('theme object can be extended', function() {
//    const tailwindMerge = extendTailwindMerge({
//        theme: {
//        'my-theme': ['hallo', 'hello'],
//        },
//        classGroups: {
//        px: [{ px: [fromTheme('my-theme')] }],
//        },
//    })

    expect(TailwindMerge::merge('p-3 p-hello p-hallo'))->toBe('p-3 p-hello p-hallo');
    expect(TailwindMerge::merge('px-3 px-hello px-hallo'))->toBe('px-hallo');
})->todo();
