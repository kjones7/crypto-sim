<?php declare(strict_types=1);

use Auryn\Injector;
use CryptoSim\Framework\Rendering\TemplateRenderer;
use CryptoSim\Framework\Rendering\TwigTemplateRendererFactory;
use CryptoSim\Framework\Rendering\TemplateDirectory;

$injector = new Injector();

$injector->delegate(
    TemplateRenderer::class,
    function () use ($injector): TemplateRenderer {
        $factory = $injector->make(TwigTemplateRendererFactory::class);
        return $factory->create();
    }
);

$injector->define(TemplateDirectory::class, [':rootDirectory' => ROOT_DIR]);

return $injector;