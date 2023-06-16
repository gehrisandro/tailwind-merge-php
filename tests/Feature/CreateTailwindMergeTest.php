<?php

use TailwindMerge\TailwindMerge;

test('createTailwindMerge works with single config function', function ($input, $output) {
    $instance = TailwindMerge::factory()
        ->withConfiguration([
            'cacheSize' => 20,
            'theme' => [],
            'classGroups' => [
                'fooKey' => [['fooKey' => ['bar', 'baz']]],
                'fooKey2' => [['fooKey' => ['qux', 'quux']], 'other-2'],
                'otherKey' => ['nother', 'group'],
            ],
            'conflictingClassGroups' => [
                'fooKey' => ['otherKey'],
                'otherKey' => ['fooKey', 'fooKey2'],
            ],
        ])
        ->make();

    expect($instance->merge($input))->toBe($output);
})->with([
    ['', ''],
    ['my-modifier:fooKey-bar my-modifier:fooKey-baz', 'my-modifier:fooKey-baz'],
    ['other-modifier:fooKey-bar other-modifier:fooKey-baz', 'other-modifier:fooKey-baz'],
    ['group fooKey-bar', 'fooKey-bar'],
    ['fooKey-bar group', 'group'],
    ['group other-2', 'group other-2'],
    ['other-2 group', 'group'],
]);
