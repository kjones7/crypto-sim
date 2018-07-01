<?php declare(strict_types=1);

namespace CryptoSim\FrontPage\Presentation;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CryptoSim\Framework\Rendering\TemplateRenderer;

final class FrontPageController
{
    private $templateRenderer;

    public function __construct(TemplateRenderer $templateRenderer)
    {
        $this->templateRenderer = $templateRenderer;
    }
    
    public function show(Request $request): Response
    {
        $content = 'Hello, ' . $request->get('name', 'visitor');
        return new Response($content);
    }
}