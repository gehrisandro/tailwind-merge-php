<?php

use TailwindMerge\TailwindMerge;

it('handles color conflicts properly', function (string $input, string $output) {
    expect(TailwindMerge::merge($input))
        ->toBe($output);
})->with([
    ['bg-grey-5 bg-hotpink', 'bg-hotpink'],
    ['hover:bg-grey-5 hover:bg-hotpink', 'hover:bg-hotpink'],
]);
