<?php declare(strict_types=1);
define('ROOT_DIR', dirname(__DIR__));
require ROOT_DIR . '/vendor/autoload.php';

use Auryn\Injector;
use Tracy\Debugger;
use josegonzalez\Dotenv;

$loader = new Dotenv\Loader(ROOT_DIR . '/app.env');
$loader->parse();
$loader->toEnv();

$debug = ($_ENV['APP_ENV'] === 'prod') ? Debugger::PRODUCTION : Debugger::DEVELOPMENT;
// TODO - When deployed, get rid of the argument for this function call
Debugger::enable($debug);

$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

$dispatcher = \FastRoute\simpleDispatcher(
    function (\FastRoute\RouteCollector $r) {
        $routes = include(ROOT_DIR . '/src/Routes.php');
        foreach ($routes as $route) {
            $r->addRoute(...$route);
        }
    }
);

    $routeInfo = $dispatcher->dispatch(
        $request->getMethod(),
        $request->getPathInfo()
    );

    switch ($routeInfo[0]) {
        case \FastRoute\Dispatcher::NOT_FOUND:
            $response = new \Symfony\Component\HttpFoundation\Response(
                'Not found',
                404
            );
            break;
        case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            $response = new \Symfony\Component\HttpFoundation\Response(
                'Method not allowed',
                405
            );
            break;
        case \FastRoute\Dispatcher::FOUND:
            [$controllerName, $method] = explode('#', $routeInfo[1]);
            $vars = $routeInfo[2];
            /** @var Injector; */
            $injector = include('Dependencies.php');

            $controller = $injector->make($controllerName);
            $response = $controller->$method($request, $vars);
            break;
    }

if (!$response instanceof \Symfony\Component\HttpFoundation\Response) {
    throw new \Exception('Controller methods must return a Response object');
}
$response->prepare($request);
$response->send();