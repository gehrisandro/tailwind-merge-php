<?php

use TailwindMerge\TailwindMerge;

test('single character separator working correctly', function ($input, $output) {
    $instance = TailwindMerge::factory()
        ->withConfiguration([
            'separator' => '_',
        ])
        ->make();

    expect($instance->merge($input))->toBe($output);
})->with([
    ['block hidden', 'hidden'],
    ['p-3 p-2', 'p-2'],
    ['!right-0 !inset-0', '!inset-0'],
    ['hover_focus_!right-0 focus_hover_!inset-0', 'focus_hover_!inset-0'],
    ['hover:focus:!right-0 focus:hover:!inset-0', 'hover:focus:!right-0 focus:hover:!inset-0'],
]);

test('multiple character separator working correctly', function ($input, $output) {
    $instance = TailwindMerge::factory()
        ->withConfiguration([
            'separator' => '__',
        ])
        ->make();

    expect($instance->merge($input))->toBe($output);
})->with([
    ['block hidden', 'hidden'],
    ['p-3 p-2', 'p-2'],
    ['!right-0 !inset-0', '!inset-0'],
    ['hover__focus__!right-0 focus__hover__!inset-0', 'focus__hover__!inset-0'],
    ['hover:focus:!right-0 focus:hover:!inset-0', 'hover:focus:!right-0 focus:hover:!inset-0'],
]);
