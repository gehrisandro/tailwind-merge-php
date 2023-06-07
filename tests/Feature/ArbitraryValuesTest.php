<?php

use TailwindMerge\TailwindMerge;

it('handles simple conflicts with arbitrary values correctly', function (string $input, string $output) {
    expect(TailwindMerge::merge($input))
        ->toBe($output);
})->with([
    ['m-[2px] m-[10px]', 'm-[10px]'],
    ['m-[2px] m-[11svmin] m-[12in] m-[13lvi] m-[14vb] m-[15vmax] m-[16mm] m-[17%] m-[18em] m-[19px] m-[10dvh]', 'm-[10dvh]'],
    ['h-[10px] h-[11cqw] h-[12cqh] h-[13cqi] h-[14cqb] h-[15cqmin] h-[16cqmax]', 'h-[16cqmax]'],
    ['z-20 z-[99]', 'z-[99]'],
    ['my-[2px] m-[10rem]', 'm-[10rem]'],
    ['cursor-pointer cursor-[grab]', 'cursor-[grab]'],
    ['m-[2px] m-[calc(100%-var(--arbitrary))]', 'm-[calc(100%-var(--arbitrary))]'],
    ['m-[2px] m-[length:var(--mystery-var)]', 'm-[length:var(--mystery-var)]'],
    ['opacity-10 opacity-[0.025]', 'opacity-[0.025]'],
    ['scale-75 scale-[1.7]', 'scale-[1.7]'],
    ['brightness-90 brightness-[1.75]', 'brightness-[1.75]'],
    // Handling of value `0`
    ['min-h-[0.5px] min-h-[0]', 'min-h-[0]'],
    ['text-[0.5px] text-[color:0]', 'text-[0.5px] text-[color:0]'],
    ['text-[0.5px] text-[--my-0]', 'text-[0.5px] text-[--my-0]'],
]);

it('handles arbitrary length conflicts with labels and modifiers correctly', function (string $input, string $output) {
    expect(TailwindMerge::merge($input))
        ->toBe($output);
})->with([
    ['hover:m-[2px] hover:m-[length:var(--c)]', 'hover:m-[length:var(--c)]'],
    ['hover:focus:m-[2px] focus:hover:m-[length:var(--c)]', 'focus:hover:m-[length:var(--c)]'],
    ['border-b border-[color:rgb(var(--color-gray-500-rgb)/50%))]', 'border-b border-[color:rgb(var(--color-gray-500-rgb)/50%))]'],
    ['border-[color:rgb(var(--color-gray-500-rgb)/50%))] border-b', 'border-[color:rgb(var(--color-gray-500-rgb)/50%))] border-b'],
    ['border-b border-[color:rgb(var(--color-gray-500-rgb)/50%))] border-some-coloooor', 'border-b border-some-coloooor'],
]);

it('handles complex arbitrary value conflicts correctly', function (string $input, string $output) {
    expect(TailwindMerge::merge($input))
        ->toBe($output);
})->with([
    ['grid-rows-[1fr,auto] grid-rows-2', 'grid-rows-2'],
    ['grid-rows-[repeat(20,minmax(0,1fr))] grid-rows-3', 'grid-rows-3'],
]);
