<?php

use TailwindMerge\TailwindMerge;

test('handles arbitrary property conflicts correctly', function () {
    expect(TailwindMerge::merge('[paint-order:markers] [paint-order:normal]'))->toBe('[paint-order:normal]');
    expect(
        TailwindMerge::merge('[paint-order:markers] [--my-var:2rem] [paint-order:normal] [--my-var:4px]'),
    )->toBe('[paint-order:normal] [--my-var:4px]');
});

test('handles arbitrary property conflicts with modifiers correctly', function () {
    expect(TailwindMerge::merge('[paint-order:markers] hover:[paint-order:normal]'))->toBe(
        '[paint-order:markers] hover:[paint-order:normal]',
    );
    expect(TailwindMerge::merge('hover:[paint-order:markers] hover:[paint-order:normal]'))->toBe(
        'hover:[paint-order:normal]',
    );
    expect(TailwindMerge::merge('hover:focus:[paint-order:markers] focus:hover:[paint-order:normal]'))->toBe(
        'focus:hover:[paint-order:normal]',
    );
    expect(
        TailwindMerge::merge('[paint-order:markers] [paint-order:normal] [--my-var:2rem] lg:[--my-var:4px]'),
    )->toBe('[paint-order:normal] [--my-var:2rem] lg:[--my-var:4px]');
});

test('handles complex arbitrary property conflicts correctly', function () {
    expect(TailwindMerge::merge('[-unknown-prop:::123:::] [-unknown-prop:url(https://hi.com)]'))->toBe(
        '[-unknown-prop:url(https://hi.com)]',
    );
});

test('handles important modifier correctly', function () {
    expect(TailwindMerge::merge('![some:prop] [some:other]'))->toBe('![some:prop] [some:other]');
    expect(TailwindMerge::merge('![some:prop] [some:other] [some:one] ![some:another]'))->toBe(
        '[some:one] ![some:another]',
    );
});
