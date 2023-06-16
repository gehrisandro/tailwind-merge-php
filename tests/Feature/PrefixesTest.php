<?php

use TailwindMerge\TailwindMerge;

it('works with a prefix correctly', function (string $input, string $output) {
    $instance = TailwindMerge::factory()
        ->withConfiguration([
            'prefix' => 'tw-',
        ])->make();

    expect($instance->merge($input))
        ->toBe($output);
})->with([
    ['tw-block tw-hidden', 'tw-hidden'],
    ['block hidden', 'block hidden'],
    ['tw-p-3 tw-p-2', 'tw-p-2'],
    ['p-3 p-2', 'p-3 p-2'],
    ['!tw-right-0 !tw-inset-0', '!tw-inset-0'],
    ['hover:focus:!tw-right-0 focus:hover:!tw-inset-0', 'focus:hover:!tw-inset-0'],
]);
