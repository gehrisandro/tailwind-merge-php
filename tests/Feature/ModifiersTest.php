<?php

use TailwindMerge\TailwindMerge;

it('conflicts across prefix modifiers', function (string $input, string $output) {
    expect(TailwindMerge::instance()->merge($input))
        ->toBe($output);
})->with([
    ['hover:block hover:inline', 'hover:inline'],
    ['hover:block hover:focus:inline', 'hover:block hover:focus:inline'],
    ['hover:block hover:focus:inline focus:hover:inline', 'hover:block focus:hover:inline'],
    ['focus-within:inline focus-within:block', 'focus-within:block'],
]);

it('conflicts across postfix modifiers', function (string $input, string $output) {
    expect(TailwindMerge::instance()->merge($input))
        ->toBe($output);
})->with([
    ['text-lg/7 text-lg/8', 'text-lg/8'],
    ['text-lg/none leading-9', 'text-lg/none leading-9'],
    ['leading-9 text-lg/none', 'text-lg/none'],
    ['w-full w-1/2', 'w-1/2'],
]);

test('conflicts across postfix modifiers with custom configuration', function ($input, $output) {
    $instance = TailwindMerge::factory()
        ->withConfiguration([
            'cacheSize' => 10,
            'theme' => [],
            'classGroups' => [
                'foo' => ['foo-1/2', 'foo-2/3'],
                'bar' => ['bar-1', 'bar-2'],
                'baz' => ['baz-1', 'baz-2'],
            ],
            'conflictingClassGroups' => [],
            'conflictingClassGroupModifiers' => [
                'baz' => ['bar'],
            ],
        ])
        ->make();

    expect($instance->merge($input))->toBe($output);
})->with([
    //    ['foo-1/2 foo-2/3', 'foo-2/3'], TODO: this one is failing, because the '/'  is considered as possible postfix modifier
    ['bar-1 bar-2', 'bar-2'],
    ['bar-1 baz-1', 'bar-1 baz-1'],
    ['bar-1/2 bar-2', 'bar-2'],
    ['bar-2 bar-1/2', 'bar-1/2'],
    ['bar-1 baz-1/2', 'baz-1/2'],
]);
