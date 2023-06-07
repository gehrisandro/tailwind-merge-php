<?php

use TailwindMerge\TailwindMerge;

it('conflicts across prefix modifiers', function (string $input, string $output) {
    expect(TailwindMerge::merge($input))
        ->toBe($output);
})->with([
    ['hover:block hover:inline', 'hover:inline'],
    ['hover:block hover:focus:inline', 'hover:block hover:focus:inline'],
    ['hover:block hover:focus:inline focus:hover:inline', 'hover:block focus:hover:inline'],
    ['focus-within:inline focus-within:block', 'focus-within:block'],
]);

it('conflicts across postfix modifiers', function (string $input, string $output) {
    expect(TailwindMerge::merge($input))
        ->toBe($output);
})->with([
    ['text-lg/7 text-lg/8', 'text-lg/8'],
    ['text-lg/none leading-9', 'text-lg/none leading-9'],
    ['leading-9 text-lg/none', 'text-lg/none'],
    ['w-full w-1/2', 'w-1/2'],
]);

test('conflicts across postfix modifiers with custom configuration', function () {
    // TODO
    //    $customTwMerge = createTailwindMerge(() => ({
    //        cacheSize: 10,
    //        theme: {},
    //        classGroups: {
    //        foo: ['foo-1/2', 'foo-2/3'],
    //            bar: ['bar-1', 'bar-2'],
    //            baz: ['baz-1', 'baz-2'],
    //        },
    //        conflictingClassGroups: {},
    //        conflictingClassGroupModifiers: {
    //        baz: ['bar'],
    //        },
    //    }));

    expect(customTwMerge('foo-1/2 foo-2/3'))->toBe('foo-2/3');
    expect(customTwMerge('bar-1 bar-2'))->toBe('bar-2');
    expect(customTwMerge('bar-1 baz-1'))->toBe('bar-1 baz-1');
    expect(customTwMerge('bar-1/2 bar-2'))->toBe('bar-2');
    expect(customTwMerge('bar-2 bar-1/2'))->toBe('bar-1/2');
    expect(customTwMerge('bar-1 baz-1/2'))->toBe('baz-1/2');
})->todo();
