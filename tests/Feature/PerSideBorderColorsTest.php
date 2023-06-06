<?php

use TailwindMerge\TailwindMerge;

test('merges classes with per-side border colors correctly', function () {
    expect(TailwindMerge::merge('border-t-some-blue border-t-other-blue'))->toBe('border-t-other-blue');
    expect(TailwindMerge::merge('border-t-some-blue border-some-blue'))->toBe('border-some-blue');
});
