<?php

use Composer\Autoload\ClassLoader;

/** @var ClassLoader $loader */
$loader = require __DIR__.'/../vendor/autoload.php';

//AnnotationRegistry::registerLoader([$loader, 'loadClass']);

return $loader;
