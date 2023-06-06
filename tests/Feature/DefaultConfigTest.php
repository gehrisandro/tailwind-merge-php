<?php

test('default config has correct types', function () {
    $defaultConfig = \TailwindMerge\Support\Config::getDefaultConfig();

    expect($defaultConfig['cacheSize'])->toBe(500);
    // @ts-expect-error
    expect($defaultConfig)->not->toHaveKey('nonExistent');
    expect($defaultConfig['classGroups']['display'][0])->toBe('block');
    expect($defaultConfig['classGroups']['overflow'][0]['overflow'][0])->toBe('auto');
    expect($defaultConfig['classGroups']['overflow'][0])->not->toHaveKey('nonExistent');
});
