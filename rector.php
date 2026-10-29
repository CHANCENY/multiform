<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/examples',
        __DIR__ . '/element.php',
        __DIR__ . '/index.php',
    ])
    ->withTypeCoverageLevel(0)
    ->withDeadCodeLevel(1)
    ->withCodeQualityLevel(1);
