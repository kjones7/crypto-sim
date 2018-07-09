<?php declare(strict_types=1);
namespace CryptoSim\User\Presentation;

use CryptoSim\Framework\Rendering\TemplateRenderer;
use CryptoSim\User\Application\DoesNicknameExistQuery;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

final class ProfileController {
    private $templateRenderer;
    private $doesNicknameExistQuery;

    public function __construct(
        TemplateRenderer $templateRenderer,
        DoesNicknameExistQuery $doesNicknameExistQuery
    ){
        $this->templateRenderer = $templateRenderer;
        $this->doesNicknameExistQuery = $doesNicknameExistQuery;
    }

    public function show(Request $request, array $vars) : Response
    {
        $nickname = $vars['nickname'];
        $template = 'PublicProfile.html.twig'; // default, if nickname is found

        if(!$this->doesNicknameExistQuery->execute($nickname)) {
            $template = 'PageNotFound.html.twig';
        }

        $content = $this->templateRenderer->render(
            $template,
            ['nickname' => $nickname]
        );

        return new Response($content);
    }
}