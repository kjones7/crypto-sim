<?php declare(strict_types=1);

define('ROOT_DIR', dirname(__DIR__));
require ROOT_DIR . '/vendor/autoload.php';
use Tracy\Debugger;

// Warning: This will only work with the Debugger::DEVELOPMENT parameter given
// TODO - Fix this later so that it changes to Debugger::Production in 
// the build process
Debugger::enable(Debugger::DEVELOPMENT);

echo 'Hello from the bootstrap file :)';