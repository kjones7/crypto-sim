<?php declare(strict_types=1);

namespace CryptoSim\Framework\Rendering;

use Twig_Loader_Filesystem;
use Twig_Environment;

final class TwigTemplateRendererFactory
{
    private $templateDirectory;

    public function __construct(TemplateDirectory $templateDirectory)
    {
        $this->templateDirectory = $templateDirectory;
    }

    public function create(): TwigTemplateRenderer
    {
        $templateDirectory = $this->templateDirectory->toString();
        $loader = new Twig_Loader_Filesystem([$templateDirectory]);
        $twigEnvironment = new Twig_Environment($loader);
        return new TwigTemplateRenderer($twigEnvironment);
    }
}