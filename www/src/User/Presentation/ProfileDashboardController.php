<?php declare(strict_types=1);

namespace CryptoSim\User\Presentation;

use CryptoSim\Framework\Rendering\TemplateRenderer;
use CryptoSim\User\Application\FriendRequestsQuery;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use CryptoSim\User\Application\FriendsListQuery;

final class ProfileDashboardController
{
    private $templateRenderer;
    private $session;
    private $friendRequestsQuery;
    private $friendsListQuery;

    public function __construct(
        TemplateRenderer $templateRenderer,
        Session $session,
        FriendRequestsQuery $friendRequestsQuery,
        FriendsListQuery $friendsListQuery
    ) {
        $this->templateRenderer = $templateRenderer;
        $this->session = $session;
        $this->friendRequestsQuery = $friendRequestsQuery;
        $this->friendsListQuery = $friendsListQuery;
    }

    public function show() : Response
    {
        $template = 'ProfileDashboard.html.twig';

        if(!$this->session->get('userId')) {
            $template = 'PageNotFound.html.twig';
        }

        $content = $this->templateRenderer->render(
            $template,
            [
                'nickname' => $this->session->get('nickname'),
                'friendRequests' => $this->friendRequestsQuery->execute(),
                'friendsList' => $this->friendsListQuery->execute()
            ]
        );

        return new Response($content);
    }
}