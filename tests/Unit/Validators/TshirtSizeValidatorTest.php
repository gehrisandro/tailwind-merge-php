<?php

use TailwindMerge\Validators\TshirtSizeValidator;

test('is t-shirt size', function ($input, $output) {
    expect(TshirtSizeValidator::validate($input))->toBe($output);
})->with([
    ['xs', true],
    ['sm', true],
    ['md', true],
    ['lg', true],
    ['xl', true],
    ['2xl', true],
    ['2.5xl', true],
    ['10xl', true],
    ['2xs', true],
    ['2lg', true],

    ['', false],
    ['hello', false],
    ['1', false],
    ['xl3', false],
    ['2xl3', false],
    ['-xl', false],
    ['[sm]', false],
]);
