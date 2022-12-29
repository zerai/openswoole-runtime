<?php declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Symfony\Set\SymfonySetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        //__DIR__ . '/src',
        //__DIR__ . '/tests'
    ]);

    $rectorConfig->skip([
        __DIR__ . '/src/LaravelRunner.php',
    ]);

    $rectorConfig->phpVersion(PhpVersion::PHP_80);

    // define sets of rules
    $rectorConfig->sets([
        /**
         * PHP
         */
        LevelSetList::UP_TO_PHP_80,

        /**
         * PHPUNIT
         */
        #PHPUnitSetList::PHPUNIT_91,

    ]);

    // register a single rule
    //$rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);
};
