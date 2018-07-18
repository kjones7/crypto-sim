<?php declare(strict_types=1);
namespace CryptoSim\User\Presentation;

use CryptoSim\Framework\Rendering\TemplateRenderer;
use CryptoSim\User\Application\DoesNicknameExistQuery;
use CryptoSim\User\Application\GetPublicUserFromNicknameQuery;
use CryptoSim\User\Domain\PublicUserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

final class ProfileController {
    private $templateRenderer;
    private $doesNicknameExistQuery;
    private $getPublicUserFromNicknameQuery;
    private $publicUserRepository;

    public function __construct(
        TemplateRenderer $templateRenderer,
        DoesNicknameExistQuery $doesNicknameExistQuery,
        GetPublicUserFromNicknameQuery $getPublicUserFromNicknameQuery,
        PublicUserRepository $publicUserRepository
    ){
        $this->templateRenderer = $templateRenderer;
        $this->doesNicknameExistQuery = $doesNicknameExistQuery;
        $this->getPublicUserFromNicknameQuery = $getPublicUserFromNicknameQuery;
        $this->publicUserRepository = $publicUserRepository;
    }

    //TODO - Refactor this method so $content doesn't get created in two different spots
    public function show(Request $request, array $vars) : Response
    {
        $nickname = $vars['nickname'];
        $template = 'PublicProfile.html.twig'; // default, if nickname is found

        if(!$this->doesNicknameExistQuery->execute($nickname)) {
            $template = 'PageNotFound.html.twig';
            $content = $this->templateRenderer->render(
                $template
            );
        } else {
            $publicUser = $this->getPublicUserFromNicknameQuery->execute($nickname);
            $isUserOnFriendsList = $this->publicUserRepository->isUserOnFriendsList($publicUser->getUserId());
            $isFriendRequestAwaitingResponse = $this->publicUserRepository->isFriendRequestAwaitingResponse($publicUser->getUserId());
            $content = $this->templateRenderer->render($template, [
                'publicUser' => $publicUser,
                'isUserOnFriendsList' => $isUserOnFriendsList,
                'isFriendRequestAwaitingResponse' => $isFriendRequestAwaitingResponse
            ]);
        }

        return new Response($content);
    }
}