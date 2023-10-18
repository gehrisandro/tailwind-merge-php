<?php

use TailwindMerge\Validators\ArbitraryImageValidator;

test('is arbitrary image', function ($input, $output) {
    expect(ArbitraryImageValidator::validate($input))->toBe($output);
})->with([
    ['[url:var(--my-url)]', true],
    ['[url(something)]', true],
    ['[url:bla]', true],
    ['[image:bla]', true],
    ['[linear-gradient(something)]', true],
    ['[repeating-conic-gradient(something)]', true],

    ['[var(--my-url)]', false],
    ['[bla]', false],
    ['url:2px', false],
    ['url(2px)', false],
]);
