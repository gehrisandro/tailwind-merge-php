<?php

use TailwindMerge\TailwindMerge;

test('handles color conflicts properly', function () {
    //    expect(TailwindMerge::merge('bg-grey-5 bg-hotpink'))->toBe('bg-hotpink');
    expect(TailwindMerge::merge('hover:bg-grey-5 hover:bg-hotpink'))->toBe('hover:bg-hotpink');
});
