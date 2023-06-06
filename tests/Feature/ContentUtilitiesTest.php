<?php

use TailwindMerge\TailwindMerge;

test('merges content utilities correctly', function () {
    expect(TailwindMerge::merge("content-['hello'] content-[attr(data-content)]"))->toBe(
        'content-[attr(data-content)]',
    );
});
