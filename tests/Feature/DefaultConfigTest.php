<?php

test('default config has correct types', function () {
    $defaultConfig = \TailwindMerge\Support\Config::getDefaultConfig();

    expect($defaultConfig)
        ->not->toHaveKey('nonExistent')
        ->cacheSize->toBe(500)
        ->classGroups->display->{0}->toBe('block')
        ->classGroups->overflow->{0}->overflow->{0}->toBe('auto')
        ->classGroups->overflow->{0}->not->toHaveKey('nonExistent');
});
