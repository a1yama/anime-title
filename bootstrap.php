<?php
/**
 * 共通bootstrap
 */

// autoloader
use Framework\PhpError;

$classLoader = require __DIR__ . '/vendor/autoload.php';

// application config
require __DIR__ . '/config/config.php';

// container
require_once __DIR__ . '/bin/Container.php';
$container = Container::getContainer();
