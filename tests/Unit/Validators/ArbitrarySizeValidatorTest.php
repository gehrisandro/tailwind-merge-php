<?php

use TailwindMerge\Validators\ArbitrarySizeValidator;

test('is arbitrary size', function ($input, $output) {
    expect(ArbitrarySizeValidator::validate($input))->toBe($output);
})->with([
    ['[size:2px]', true],
    ['[size:bla]', true],

    ['[2px]', false],
    ['[bla]', false],
    ['size:2px', false],
]);
