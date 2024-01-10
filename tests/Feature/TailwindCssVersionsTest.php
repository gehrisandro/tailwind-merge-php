<?php

use TailwindMerge\TailwindMerge;

it('supports Tailwind CSS v3.3 features', function (string|array $input, string $output) {
    expect(TailwindMerge::instance()->merge($input))
        ->toBe($output);
})->with([
    ['text-red text-lg/7 text-lg/8', 'text-red text-lg/8'],
    [[
        'start-0 start-1',
        'end-0 end-1',
        'ps-0 ps-1 pe-0 pe-1',
        'ms-0 ms-1 me-0 me-1',
        'rounded-s-sm rounded-s-md rounded-e-sm rounded-e-md',
        'rounded-ss-sm rounded-ss-md rounded-ee-sm rounded-ee-md',
    ], 'start-1 end-1 ps-1 pe-1 ms-1 me-1 rounded-s-md rounded-e-md rounded-ss-md rounded-ee-md'],
    ['start-0 end-0 inset-0 ps-0 pe-0 p-0 ms-0 me-0 m-0 rounded-ss rounded-es rounded-s', 'inset-0 p-0 m-0 rounded-s'],
    ['hyphens-auto hyphens-manual', 'hyphens-manual'],
    ['from-0% from-10% from-[12.5%] via-0% via-10% via-[12.5%] to-0% to-10% to-[12.5%]', 'from-[12.5%] via-[12.5%] to-[12.5%]'],
    ['from-0% from-red', 'from-0% from-red'],
    ['list-image-none list-image-[url(./my-image.png)] list-image-[var(--value)]', 'list-image-[var(--value)]'],
    ['caption-top caption-bottom', 'caption-bottom'],
    ['line-clamp-2 line-clamp-none line-clamp-[10]', 'line-clamp-[10]'],
    ['delay-150 delay-0 duration-150 duration-0', 'delay-0 duration-0'],
    ['justify-normal justify-center justify-stretch', 'justify-stretch'],
    ['content-normal content-center content-stretch', 'content-stretch'],
    ['whitespace-nowrap whitespace-break-spaces', 'whitespace-break-spaces'],
]);

it('supports Tailwind CSS v3.4 features', function (string|array $input, string $output) {
    expect(TailwindMerge::instance()->merge($input))
        ->toBe($output);
})->with([
    ['h-svh h-dvh w-svw w-dvw', 'h-dvh w-dvw'],
    ['has-[[data-potato]]:p-1 has-[[data-potato]]:p-2 group-has-[:checked]:grid group-has-[:checked]:flex', 'has-[[data-potato]]:p-2 group-has-[:checked]:flex'],
    ['text-wrap text-pretty', 'text-pretty'],
    ['w-5 h-3 size-10 w-12', 'size-10 w-12'],
    ['grid-cols-2 grid-cols-subgrid grid-rows-5 grid-rows-subgrid', 'grid-cols-subgrid grid-rows-subgrid'],
    ['min-w-0 min-w-50 min-w-px max-w-0 max-w-50 max-w-px', 'min-w-px max-w-px'],
    ['forced-color-adjust-none forced-color-adjust-auto', 'forced-color-adjust-auto'],
    ['appearance-none appearance-auto', 'appearance-auto'],
    ['float-start float-end clear-start clear-end', 'float-end clear-end'],
    ['*:p-10 *:p-20 hover:*:p-10 hover:*:p-20', '*:p-20 hover:*:p-20'],
]);
