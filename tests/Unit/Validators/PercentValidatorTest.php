<?php

use TailwindMerge\Validators\PercentValidator;

test('is number', function ($input, $output) {
    expect(PercentValidator::validate($input))->toBe($output);
})->with([
    ['1%', true],
    ['100.001%', true],
    ['.01%', true],
    ['0%', true],
    ['0', false],
    ['one%', false],
]);
