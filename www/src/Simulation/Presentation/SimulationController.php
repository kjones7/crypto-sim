<?php declare(strict_types=1);

namespace CryptoSim\Simulation\Presentation;

use CryptoSim\Simulation\Domain\PortfolioRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use CryptoSim\Framework\Rendering\TemplateRenderer;

final class SimulationController
{
    private $templateRenderer;
    private $portfolioRepository;

    public function __construct(
        TemplateRenderer $templateRenderer,
        PortfolioRepository $portfolioRepository
    ) {
        $this->templateRenderer = $templateRenderer;
        $this->portfolioRepository = $portfolioRepository;
    }

    public function show(Request $request, array $vars) {
        $portfolioId = $vars['portfolioId'];
        $template = 'Simulation.html.twig';

        //TODO - Validate the $portfolioId once you get the portfolio data from database
        $portfolio = $this->portfolioRepository->getPortfolioFromId($portfolioId);

        if(!$portfolio) {
            $template = 'PageNotFound.html.twig';
        }

        $content = $this->templateRenderer->render($template, [
            'portfolioId' => $portfolioId,
            'portfolio' => $portfolio
        ]);
        return new Response($content);
    }
}