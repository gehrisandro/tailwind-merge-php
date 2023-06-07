<?php

use TailwindMerge\TailwindMerge;

it('handles pseudo variants conflicts properly', function (string $input, string $output) {
    expect(TailwindMerge::merge($input))
        ->toBe($output);
})->with([
    ['empty:p-2 empty:p-3', 'empty:p-3'],
    ['hover:empty:p-2 hover:empty:p-3', 'hover:empty:p-3'],
    ['read-only:p-2 read-only:p-3', 'read-only:p-3'],
]);

it('handles pseudo variant group conflicts properly', function (string $input, string $output) {
    expect(TailwindMerge::merge($input))
        ->toBe($output);
})->with([
    ['group-empty:p-2 group-empty:p-3', 'group-empty:p-3'],
    ['peer-empty:p-2 peer-empty:p-3', 'peer-empty:p-3'],
    ['group-empty:p-2 peer-empty:p-3', 'group-empty:p-2 peer-empty:p-3'],
    ['hover:group-empty:p-2 hover:group-empty:p-3', 'hover:group-empty:p-3'],
    ['group-read-only:p-2 group-read-only:p-3', 'group-read-only:p-3'],
]);
