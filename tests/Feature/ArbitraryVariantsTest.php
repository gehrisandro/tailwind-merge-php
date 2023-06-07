<?php

use TailwindMerge\TailwindMerge;

it('basic arbitrary variants', function (string $input, string $output) {
    expect(TailwindMerge::merge($input))
        ->toBe($output);
})->with([
    ['[&>*]:underline [&>*]:line-through', '[&>*]:line-through'],
    ['[&>*]:underline [&>*]:line-through [&_div]:line-through', '[&>*]:line-through [&_div]:line-through'],
    ['supports-[display:grid]:flex supports-[display:grid]:grid', 'supports-[display:grid]:grid'],
]);

it('arbitrary variants with modifiers', function (string $input, string $output) {
    expect(TailwindMerge::merge($input))
        ->toBe($output);
})->with([
    ['dark:lg:hover:[&>*]:underline dark:lg:hover:[&>*]:line-through', 'dark:lg:hover:[&>*]:line-through'],
    ['dark:lg:hover:[&>*]:underline dark:hover:lg:[&>*]:line-through', 'dark:hover:lg:[&>*]:line-through'],
    'Whether a modifier is before or after arbitrary variant matters' => ['hover:[&>*]:underline [&>*]:hover:line-through', 'hover:[&>*]:underline [&>*]:hover:line-through'],
    ['hover:dark:[&>*]:underline dark:hover:[&>*]:underline dark:[&>*]:hover:line-through', 'dark:hover:[&>*]:underline dark:[&>*]:hover:line-through'],
]);

it('arbitrary variants with complex syntax in them', function (string $input, string $output) {
    expect(TailwindMerge::merge($input))
        ->toBe($output);
})->with([
    ['[@media_screen{@media(hover:hover)}]:underline [@media_screen{@media(hover:hover)}]:line-through', '[@media_screen{@media(hover:hover)}]:line-through'],
    ['hover:[@media_screen{@media(hover:hover)}]:underline hover:[@media_screen{@media(hover:hover)}]:line-through', 'hover:[@media_screen{@media(hover:hover)}]:line-through'],
]);

test('arbitrary variants with attribute selectors', function () {
    expect(TailwindMerge::merge('[&[data-open]]:underline [&[data-open]]:line-through'))->toBe(
        '[&[data-open]]:line-through',
    );
});

test('arbitrary variants with multiple attribute selectors', function () {
    expect(
        TailwindMerge::merge(
            '[&[data-foo][data-bar]:not([data-baz])]:underline [&[data-foo][data-bar]:not([data-baz])]:line-through',
        ),
    )->toBe('[&[data-foo][data-bar]:not([data-baz])]:line-through');
});

it('multiple arbitrary variants', function (string $input, string $output) {
    expect(TailwindMerge::merge($input))
        ->toBe($output);
})->with([
    ['[&>*]:[&_div]:underline [&>*]:[&_div]:line-through', '[&>*]:[&_div]:line-through'],
    ['[&>*]:[&_div]:underline [&_div]:[&>*]:line-through', '[&>*]:[&_div]:underline [&_div]:[&>*]:line-through'],
    ['hover:dark:[&>*]:focus:disabled:[&_div]:underline dark:hover:[&>*]:disabled:focus:[&_div]:line-through', 'dark:hover:[&>*]:disabled:focus:[&_div]:line-through'],
    ['hover:dark:[&>*]:focus:[&_div]:disabled:underline dark:hover:[&>*]:disabled:focus:[&_div]:line-through', 'hover:dark:[&>*]:focus:[&_div]:disabled:underline dark:hover:[&>*]:disabled:focus:[&_div]:line-through'],
]);

it('arbitrary variants with arbitrary properties', function (string $input, string $output) {
    expect(TailwindMerge::merge($input))
        ->toBe($output);
})->with([
    ['[&>*]:[color:red] [&>*]:[color:blue]', '[&>*]:[color:blue]'],
    ['[&[data-foo][data-bar]:not([data-baz])]:nod:noa:[color:red] [&[data-foo][data-bar]:not([data-baz])]:noa:nod:[color:blue]', '[&[data-foo][data-bar]:not([data-baz])]:noa:nod:[color:blue]'],
]);
