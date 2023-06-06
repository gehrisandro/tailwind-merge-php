<?php

use TailwindMerge\TailwindMerge;

test('handles pseudo variants conflicts properly', function() {
    expect(TailwindMerge::merge('empty:p-2 empty:p-3'))->toBe('empty:p-3');
    expect(TailwindMerge::merge('hover:empty:p-2 hover:empty:p-3'))->toBe('hover:empty:p-3');
    expect(TailwindMerge::merge('read-only:p-2 read-only:p-3'))->toBe('read-only:p-3');
});

test('handles pseudo variant group conflicts properly', function() {
    expect(TailwindMerge::merge('group-empty:p-2 group-empty:p-3'))->toBe('group-empty:p-3');
    expect(TailwindMerge::merge('peer-empty:p-2 peer-empty:p-3'))->toBe('peer-empty:p-3');
    expect(TailwindMerge::merge('group-empty:p-2 peer-empty:p-3'))->toBe('group-empty:p-2 peer-empty:p-3');
    expect(TailwindMerge::merge('hover:group-empty:p-2 hover:group-empty:p-3'))->toBe('hover:group-empty:p-3');
    expect(TailwindMerge::merge('group-read-only:p-2 group-read-only:p-3'))->toBe('group-read-only:p-3');
});
