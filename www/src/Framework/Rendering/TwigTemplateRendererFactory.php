<?php declare(strict_types=1);

namespace CryptoSim\Framework\Rendering;

use Twig_Loader_Filesystem;
use Twig_Environment;

final class TwigTemplateRendererFactory
{
    public function create(): TwigTemplateRenderer
    {
        $loader = new Twig_Loader_Filesystem([]);
        $twigEnvironment = new Twig_Environment($loader);
        return new TwigTemplateRenderer($twigEnvironment);
    }
}