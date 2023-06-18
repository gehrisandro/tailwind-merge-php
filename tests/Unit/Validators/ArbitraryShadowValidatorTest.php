<?php

use TailwindMerge\Validators\ArbitraryShadowValidator;

test('is arbitrary shadow', function ($input, $output) {
    expect(ArbitraryShadowValidator::validate($input))->toBe($output);
})->with([
    ['[0_35px_60px_-15px_rgba(0,0,0,0.3)]', true],
    ['[0_0_#00f]', true],
    ['[.5rem_0_rgba(5,5,5,5)]', true],
    ['[-.5rem_0_#123456]', true],
    ['[0.5rem_-0_#123456]', true],
    ['[0.5rem_-0.005vh_#123456]', true],
    ['[0.5rem_-0.005vh]', true],

    ['[rgba(5,5,5,5)]', false],
    ['[#00f]', false],
    ['[something-else]', false],
]);
