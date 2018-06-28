<?php declare(strict_types=1);

define('ROOT_DIR', dirname(__DIR__));
require ROOT_DIR . '/vendor/autoload.php';
// Warning: This will only work with the 'Debugger::DEVELOPMENT' parameter given
\Tracy\Debugger::enable();

echo 'Hello from the bootstrap file :)';