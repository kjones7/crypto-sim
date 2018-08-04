<?php declare(strict_types=1);

namespace CryptoSim\Simulation\Presentation;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use CryptoSim\Framework\Rendering\TemplateRenderer;

final class SimulationController
{
    private $templateRenderer;

    public function __construct(TemplateRenderer $templateRenderer)
    {
        $this->templateRenderer = $templateRenderer;
    }

    public function show(Request $request, array $vars) {
        $portfolioId = $vars['portfolioId'];

        //TODO - Validate the $portfolioId once you get the portfolio data from database

        $content = $this->templateRenderer->render('Simulation.html.twig', [
            'portfolioId' => $portfolioId
        ]);
        return new Response($content);
    }
}