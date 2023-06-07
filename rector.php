<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__.'/src',
    ]);

    $rectorConfig->skip([
        __DIR__.'/src/Support/Config.php' => \Rector\TypeDeclaration\Rector\ClassMethod\AddArrayReturnDocTypeRector::class,
    ]);

    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_81,
        SetList::CODE_QUALITY,
        //        SetList::DEAD_CODE, // TODO: Enable when everything is finished
        SetList::EARLY_RETURN,
        SetList::TYPE_DECLARATION,
    ]);
};
