<?php

use TailwindMerge\Validators\AnyValueValidator;

test('is any value', function ($input, $output) {
    expect(AnyValueValidator::validate($input))->toBe($output);
})->with([
    ['', true],
    ['something', true],
]);
