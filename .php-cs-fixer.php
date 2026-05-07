<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in('src')
    ->in('tests')
;

return (new Config())->setRules([
        '@Symfony' => true,
        'full_opening_tag' => false,
        'phpdoc_separation' => false,
        'global_namespace_import' => ['import_classes' => true],
    ])
    ->setFinder($finder)
;
