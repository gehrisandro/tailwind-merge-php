<?php

use TailwindMerge\TailwindMerge;

it('handles arbitrary property conflicts correctly', function (string $input, string $output) {
    expect(TailwindMerge::instance()->merge($input))
        ->toBe($output);
})->with([
    ['[paint-order:markers] [paint-order:normal]', '[paint-order:normal]'],
    ['[paint-order:markers] [--my-var:2rem] [paint-order:normal] [--my-var:4px]', '[paint-order:normal] [--my-var:4px]'],
    ['[--first-var:1rem] [--second-var:2rem]', '[--first-var:1rem] [--second-var:2rem]'],
]);

it('handles arbitrary property conflicts with modifiers correctly', function (string $input, string $output) {
    expect(TailwindMerge::instance()->merge($input))
        ->toBe($output);
})->with([
    ['[paint-order:markers] hover:[paint-order:normal]', '[paint-order:markers] hover:[paint-order:normal]'],
    ['hover:[paint-order:markers] hover:[paint-order:normal]', 'hover:[paint-order:normal]'],
    ['hover:focus:[paint-order:markers] focus:hover:[paint-order:normal]', 'focus:hover:[paint-order:normal]'],
    ['[paint-order:markers] [paint-order:normal] [--my-var:2rem] lg:[--my-var:4px]', '[paint-order:normal] [--my-var:2rem] lg:[--my-var:4px]'],
]);

test('handles complex arbitrary property conflicts correctly', function () {
    expect(TailwindMerge::instance()->merge('[-unknown-prop:::123:::] [-unknown-prop:url(https://hi.com)]'))->toBe(
        '[-unknown-prop:url(https://hi.com)]',
    );
});

it('handles important modifier correctly', function (string $input, string $output) {
    expect(TailwindMerge::instance()->merge($input))
        ->toBe($output);
})->with([
    ['![some:prop] [some:other]', '![some:prop] [some:other]'],
    ['![some:prop] [some:other] [some:one] ![some:another]', '[some:one] ![some:another]'],
]);
