<?php

use TailwindMerge\Validators\ArbitraryLengthValidator;

test('is arbitrary length', function ($input, $output) {
    expect(ArbitraryLengthValidator::validate($input))->toBe($output);
})->with([
    ['[3.7%]', true],
    ['[481px]', true],
    ['[19.1rem]', true],
    ['[50vw]', true],
    ['[56vh]', true],
    ['[length:var(--arbitrary)]', true],
    ['1', false],
    ['3px', false],
    ['1d5', false],
    ['[1]', false],
    ['[12px', false],
    ['12px]', false],
    ['one', false],
]);
