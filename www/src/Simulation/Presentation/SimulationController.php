<?php declare(strict_types=1);

namespace CryptoSim\Simulation\Presentation;

use Symfony\Component\HttpFoundation\Response;
use CryptoSim\Framework\Rendering\TemplateRenderer;

final class SimulationController
{
    private $templateRenderer;

    public function __construct(TemplateRenderer $templateRenderer)
    {
        $this->templateRenderer = $templateRenderer;
    }

    public function show() {
        $content = $this->templateRenderer->render('Simulation.html.twig');
        return new Response($content);
    }
}