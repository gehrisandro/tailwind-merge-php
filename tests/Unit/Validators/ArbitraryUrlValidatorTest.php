<?php

use TailwindMerge\Validators\ArbitraryUrlValidator;

test('is arbitrary url', function ($input, $output) {
    expect(ArbitraryUrlValidator::validate($input))->toBe($output);
})->with([
    ['[url:var(--my-url)]', true],
    ['[url(something)]', true],
    ['[url:bla]', true],

    ['[var(--my-url)]', false],
    ['[bla]', false],
    ['url:2px', false],
    ['url(2px)', false],
]);
