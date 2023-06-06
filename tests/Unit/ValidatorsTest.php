<?php

use TailwindMerge\Support\Config;

test('isLength', function ($input, $output) {
    expect(Config::isLength($input))->toBe($output);
})->with([
    ['1', true],
    ['1023713', true],
    ['1.5', true],
    ['1231.503761', true],
    ['px', true],
    ['full', true],
    ['screen', true],
    ['1/2', true],
    ['123/345', true],
    ['[3.7%]', true],
    ['[481px]', true],
    ['[19.1rem]', true],
    ['[50vw]', true],
    ['[56vh]', true],
    ['[length:var(--arbitrary)]', true],
    ['1d5', false],
    ['[1]', false],
    ['[12px', false],
    ['12px]', false],
    ['one', false],
]);

test('isArbitraryLength', function ($input, $output) {
    expect(Config::isArbitraryLength($input))->toBe($output);
})->with([
    ['[3.7%]', true],
    ['[481px]', true],
    ['[19.1rem]', true],
    ['[50vw]', true],
    ['[56vh]', true],
    ['[length:var(--arbitrary)]', true],
    ['1', false],
    ['3px', false],
    ['1d5', false],
    ['[1]', false],
    ['[12px', false],
    ['12px]', false],
    ['one', false],
]);

test('isInteger', function ($input, $output) {
    expect(Config::isInteger($input))->toBe($output);
})->with([
    ['1', true],
    ['123', true],
    ['8312', true],
    ['[8312]', true],
    ['[2]', true],
    ['[8312px]', false],
    ['[8312%]', false],
    ['[8312rem]', false],
    ['8312.2', false],
    ['1.2', false],
    ['one', false],
    ['1/2', false],
    ['1%', false],
    ['1px', false],
]);

test('isArbitraryValue', function ($input, $output) {
    expect(Config::isArbitraryValue($input))->toBe($output);
})->with([
    ['[1]', true],
    ['[bla]', true],
    ['[not-an-arbitrary-value?]', true],
    ['[auto,auto,minmax(0,1fr),calc(100vw-50%)]', true],

    ['[]', false],
    ['[1', false],
    ['1]', false],
    ['1', false],
    ['one', false],
    ['o[n]e', false],
]);

test('isAny', function ($input, $output) {
    expect(Config::isAny($input))->toBe($output);
})->with([
    ['', true],
    ['something', true],
]);

test('isTshirtSize', function ($input, $output) {
    expect(Config::isTshirtSize($input))->toBe($output);
})->with([
    ['xs', true],
    ['sm', true],
    ['md', true],
    ['lg', true],
    ['xl', true],
    ['2xl', true],
    ['2.5xl', true],
    ['10xl', true],
    ['2xs', true],
    ['2lg', true],

    ['', false],
    ['hello', false],
    ['1', false],
    ['xl3', false],
    ['2xl3', false],
    ['-xl', false],
    ['[sm]', false],
]);

test('isArbitrarySize', function ($input, $output) {
    expect(Config::isArbitrarySize($input))->toBe($output);
})->with([
    ['[size:2px]', true],
    ['[size:bla]', true],

    ['[2px]', false],
    ['[bla]', false],
    ['size:2px', false],
]);

test('isArbitraryPosition', function ($input, $output) {
    expect(Config::isArbitraryPosition($input))->toBe($output);
})->with([
    ['[position:2px]', true],
    ['[position:bla]', true],

    ['[2px]', false],
    ['[bla]', false],
    ['position:2px', false],
]);

test('isArbitraryUrl', function ($input, $output) {
    expect(Config::isArbitraryUrl($input))->toBe($output);
})->with([
    ['[url:var(--my-url)]', true],
    ['[url(something)]', true],
    ['[url:bla]', true],

    ['[var(--my-url)]', false],
    ['[bla]', false],
    ['url:2px', false],
    ['url(2px)', false],
]);

test('isArbitraryNumber', function ($input, $output) {
    expect(Config::isArbitraryNumber($input))->toBe($output);

})->with([
    ['[number:black]', true],
    ['[number:bla]', true],
    ['[number:230]', true],
    ['[450]', true],

    ['[2px]', false],
    ['[bla]', false],
    ['[black]', false],
    ['black', false],
    ['450', false],
]);

test('isArbitraryShadow', function ($input, $output) {
    expect(Config::isArbitraryShadow($input))->toBe($output);
})->with([
    ['[0_35px_60px_-15px_rgba(0,0,0,0.3)]', true],
    ['[0_0_#00f]', true],
    ['[.5rem_0_rgba(5,5,5,5)]', true],
    ['[-.5rem_0_#123456]', true],
    ['[0.5rem_-0_#123456]', true],
    ['[0.5rem_-0.005vh_#123456]', true],
    ['[0.5rem_-0.005vh]', true],

    ['[rgba(5,5,5,5)]', false],
    ['[#00f]', false],
    ['[something-else]', false],
]);

test('isPercent', function ($input, $output) {
    expect(Config::isPercent($input))->toBe($output);
})->with([
    ['1%', true],
    ['100.001%', true],
    ['.01%', true],
    ['0%', true],
    ['0', false],
    ['one%', false],
]);
