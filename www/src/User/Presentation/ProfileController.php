<?php declare(strict_types=1);
namespace CryptoSim\User\Presentation;

use CryptoSim\Framework\Rendering\TemplateRenderer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

final class ProfileController {
    private $templateRenderer;

    public function __construct(TemplateRenderer $templateRenderer)
    {
        $this->templateRenderer = $templateRenderer;
    }

    public function show(Request $request, array $vars) : Response
    {
        $content = $this->templateRenderer->render(
            'PublicProfile.html.twig',
            ['nickname' => $vars['nickname']]
        );

        return new Response($content);
    }
}