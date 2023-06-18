<?php

use TailwindMerge\Validators\ArbitraryValueValidator;

test('is arbitrary value', function ($input, $output) {
    expect(ArbitraryValueValidator::validate($input))->toBe($output);
})->with([
    ['[1]', true],
    ['[bla]', true],
    ['[not-an-arbitrary-value?]', true],
    ['[auto,auto,minmax(0,1fr),calc(100vw-50%)]', true],

    ['[]', false],
    ['[1', false],
    ['1]', false],
    ['1', false],
    ['one', false],
    ['o[n]e', false],
]);
