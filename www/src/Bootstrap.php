<?php declare(strict_types=1);

define('ROOT_DIR', dirname(__DIR__));
require ROOT_DIR . '/vendor/autoload.php';
require ROOT_DIR . '/TracyVariable.php';
use Tracy\Debugger;

// Warning: This will only work with the Debugger::DEVELOPMENT parameter given
Debugger::enable($tracy);

echo 'Hello from the bootstrap file :)';