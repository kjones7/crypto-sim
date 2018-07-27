<?php declare(strict_types=1);

namespace CryptoSim\User\Presentation;

use CryptoSim\Framework\Rendering\TemplateRenderer;
use CryptoSim\User\Application\FriendRequestsQuery;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use CryptoSim\User\Domain\FriendsListRepository;

final class ProfileDashboardController
{
    private $templateRenderer;
    private $session;
    private $friendRequestsQuery;
    private $friendsListRepository;

    public function __construct(
        TemplateRenderer $templateRenderer,
        Session $session,
        FriendRequestsQuery $friendRequestsQuery,
        FriendsListRepository $friendsListRepository
    ) {
        $this->templateRenderer = $templateRenderer;
        $this->session = $session;
        $this->friendRequestsQuery = $friendRequestsQuery;
        $this->friendsListRepository = $friendsListRepository;
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
                'friendsList' => $this->getFriendsList()
            ]
        );

        return new Response($content);
    }

    private function getFriendsList()
    {
        $currentUserId = $this->session->get('userId');

        $userIdsOfFriends = $this->friendsListRepository->getUserIdsOfFriendsFromUserId($currentUserId);

        $friendsList = [];
        foreach($userIdsOfFriends as $userId) {
            $friendsList[] = $this->friendsListRepository->createFriendFromUserId($userId);
        }

        return $friendsList;
    }
}