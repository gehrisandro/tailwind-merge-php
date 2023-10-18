<?php

use TailwindMerge\Validators\IntegerValidator;

test('is integer', function ($input, $output) {
    expect(IntegerValidator::validate($input))->toBe($output);
})->with([
    ['1', true],
    ['123', true],
    ['8312', true],
    ['[8312]', false],
    ['[2]', false],
    ['[8312px]', false],
    ['[8312%]', false],
    ['[8312rem]', false],
    ['8312.2', false],
    ['1.2', false],
    ['one', false],
    ['1/2', false],
    ['1%', false],
    ['1px', false],
]);
