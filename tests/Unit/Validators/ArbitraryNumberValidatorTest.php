<?php

use TailwindMerge\Validators\ArbitraryNumberValidator;

test('is arbitrary number', function ($input, $output) {
    expect(ArbitraryNumberValidator::validate($input))->toBe($output);
})->with([
    ['[number:black]', true],
    ['[number:bla]', true],
    ['[number:230]', true],
    ['[450]', true],

    ['[2px]', false],
    ['[bla]', false],
    ['[black]', false],
    ['black', false],
    ['450', false],
]);
