<?php

use TailwindMerge\TailwindMerge;

test('conflicts across prefix modifiers', function () {
    expect(TailwindMerge::merge('hover:block hover:inline'))->toBe('hover:inline');
    expect(TailwindMerge::merge('hover:block hover:focus:inline'))->toBe('hover:block hover:focus:inline');
    expect(TailwindMerge::merge('hover:block hover:focus:inline focus:hover:inline'))->toBe(
        'hover:block focus:hover:inline',
    );
    expect(TailwindMerge::merge('focus-within:inline focus-within:block'))->toBe('focus-within:block');
});

test('conflicts across postfix modifiers', function () {
    expect(TailwindMerge::merge('text-lg/7 text-lg/8'))->toBe('text-lg/8');
    expect(TailwindMerge::merge('text-lg/none leading-9'))->toBe('text-lg/none leading-9');
    expect(TailwindMerge::merge('leading-9 text-lg/none'))->toBe('text-lg/none');
    expect(TailwindMerge::merge('w-full w-1/2'))->toBe('w-1/2');

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
    //
    //    expect(customTwMerge('foo-1/2 foo-2/3'))->toBe('foo-2/3');
    //    expect(customTwMerge('bar-1 bar-2'))->toBe('bar-2');
    //    expect(customTwMerge('bar-1 baz-1'))->toBe('bar-1 baz-1');
    //    expect(customTwMerge('bar-1/2 bar-2'))->toBe('bar-2');
    //    expect(customTwMerge('bar-2 bar-1/2'))->toBe('bar-1/2');
    //    expect(customTwMerge('bar-1 baz-1/2'))->toBe('baz-1/2');
});
