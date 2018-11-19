<?php declare(strict_types=1);

namespace CryptoSim\User\Presentation;

use CryptoSim\Framework\Rendering\TemplateRenderer;
use CryptoSim\Portfolio\Domain\GroupRepository;
use CryptoSim\Portfolio\Domain\PortfolioRepository;
use CryptoSim\User\Application\FriendRequestsQuery;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use CryptoSim\User\Domain\FriendsListRepository;

// TODO - Rename this to DashboardController
final class ProfileDashboardController
{
    private $templateRenderer;
    private $session;
    private $friendRequestsQuery;
    private $friendsListRepository;
    private $portfolioRepository;
    private $groupRepository;

    public function __construct(
        TemplateRenderer $templateRenderer,
        Session $session,
        FriendRequestsQuery $friendRequestsQuery,
        FriendsListRepository $friendsListRepository,
        PortfolioRepository $portfolioRepository,
        GroupRepository $groupRepository
    ) {
        $this->templateRenderer = $templateRenderer;
        $this->session = $session;
        $this->friendRequestsQuery = $friendRequestsQuery;
        $this->friendsListRepository = $friendsListRepository;
        $this->portfolioRepository = $portfolioRepository;
        $this->groupRepository = $groupRepository;
    }

    public function show() : Response
    {
        $template = 'ProfileDashboard.html.twig';

        // TODO - use permissions instead of directly using session (use RBAC User class instead of session)
        if(!$this->session->get('userId')) {
            $template = 'PageNotFound.html.twig';
        }

        $content = $this->templateRenderer->render(
            $template,
            [
                'nickname' => $this->session->get('nickname'), // TODO - Use RBAC User
                'friendRequests' => $this->friendRequestsQuery->execute(),
                'friendsList' => $this->getFriendsList(),
                'portfolios' => $this->portfolioRepository->getPortfoliosFromUserId($this->session->get('userId')), // TODO - Use RBAC User
                'groupInvites' => $this->groupRepository->getGroupInvitesForUser($this->session->get('userId'))
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