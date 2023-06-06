<?php

use TailwindMerge\TailwindMerge;

test('merges standalone classes from same group correctly', function(){
    expect(TailwindMerge::merge('inline block'))->toBe('block');
    expect(TailwindMerge::merge('hover:block hover:inline'))->toBe('hover:inline');
    expect(TailwindMerge::merge('hover:block hover:block'))->toBe('hover:block');
    expect(TailwindMerge::merge('inline hover:inline focus:inline hover:block hover:focus:block'))->toBe(
        'inline focus:inline hover:block hover:focus:block',
    );
    expect(TailwindMerge::merge('underline line-through'))->toBe('line-through');
    expect(TailwindMerge::merge('line-through no-underline'))->toBe('no-underline');
});
