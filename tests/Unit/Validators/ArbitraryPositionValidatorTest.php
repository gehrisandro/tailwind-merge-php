<?php

use TailwindMerge\Validators\ArbitraryPositionValidator;

test('is arbitrary position', function ($input, $output) {
    expect(ArbitraryPositionValidator::validate($input))->toBe($output);
})->with([
    ['[position:2px]', true],
    ['[position:bla]', true],

    ['[2px]', false],
    ['[bla]', false],
    ['position:2px', false],
]);
