<?php declare(strict_types=1);

use Auryn\Injector;
use CryptoSim\Framework\Rendering\TemplateRenderer;
use CryptoSim\Framework\Rendering\TwigTemplateRendererFactory;
use CryptoSim\Framework\Rendering\TemplateDirectory;
use Doctrine\DBAL\Connection;
use CryptoSim\Framework\Dbal\ConnectionFactory;
use CryptoSim\Framework\Dbal\DatabaseUrl;

$injector = new Injector();

$injector->delegate(
    TemplateRenderer::class,
    function () use ($injector): TemplateRenderer {
        $factory = $injector->make(TwigTemplateRendererFactory::class);
        return $factory->create();
    }
);

$injector->define(
    DatabaseUrl::class,
    [':url' => 'mysql://' . $_ENV['DB_USER'] . ':' . $_ENV['DB_PASS'] . '@' . $_ENV['DB_HOST'] . '/' . $_ENV['DB_NAME']]
);

$injector->delegate(Connection::class, function () use ($injector): Connection {
    $factory = $injector->make(ConnectionFactory::class);
    return $factory->create();
});

$injector->define(TemplateDirectory::class, [':rootDirectory' => ROOT_DIR]);

// Database connection will be reused instead of being created every time a connection is needed
$injector->share(Connection::class);

return $injector;