<?php

use TailwindMerge\TailwindMerge;

it('merges tailwind classes with important modifier correctly', function (string $input, string $output) {
    expect(TailwindMerge::instance()->merge($input))
        ->toBe($output);
})->with([
    ['!font-medium !font-bold', '!font-bold'],
    ['!font-medium !font-bold font-thin', '!font-bold font-thin'],
    ['!right-2 !-inset-x-px', '!-inset-x-px'],
    ['focus:!inline focus:!block', 'focus:!block'],
]);
