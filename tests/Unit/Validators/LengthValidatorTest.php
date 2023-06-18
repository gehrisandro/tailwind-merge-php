<?php

use TailwindMerge\Validators\LengthValidator;

test('is length', function ($input, $output) {
    expect(LengthValidator::validate($input))->toBe($output);
})->with([
    ['1', true],
    ['1023713', true],
    ['1.5', true],
    ['1231.503761', true],
    ['px', true],
    ['full', true],
    ['screen', true],
    ['1/2', true],
    ['123/345', true],
    ['[3.7%]', true],
    ['[481px]', true],
    ['[19.1rem]', true],
    ['[50vw]', true],
    ['[56vh]', true],
    ['[length:var(--arbitrary)]', true],
    ['1d5', false],
    ['[1]', false],
    ['[12px', false],
    ['12px]', false],
    ['one', false],
]);
