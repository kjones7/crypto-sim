<?php declare(strict_types=1);

namespace CryptoSim\Dashboard\Presentation;

use CryptoSim\Framework\Rbac\AuthenticatedUser;
use CryptoSim\Framework\Rbac\User;
use CryptoSim\Framework\Rendering\TemplateRenderer;
use CryptoSim\Portfolio\Application\PortfoliosQuery;
use CryptoSim\Portfolio\Domain\GroupRepository;
use CryptoSim\Portfolio\Domain\PortfolioRepository;
use CryptoSim\User\Application\CreatePortfolioFromGroupInvite;
use CryptoSim\User\Application\CreatePortfolioFromGroupInviteHandler;
use CryptoSim\User\Application\FriendRequestsQuery;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CryptoSim\User\Domain\FriendsListRepository;
use CryptoSim\Framework\Rbac\Permission\CanSeeDashboard;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class DashboardController
{
    private $templateRenderer;
    private $session;
    private $friendRequestsQuery;
    private $friendsListRepository;
    private $portfolioRepository;
    private $groupRepository;
    private $user;
    private $createPortfolioFromGroupInviteHandler;
    private $portfoliosQuery;

    public function __construct(
        TemplateRenderer $templateRenderer,
        SessionInterface $session,
        FriendRequestsQuery $friendRequestsQuery,
        FriendsListRepository $friendsListRepository,
        PortfolioRepository $portfolioRepository,
        GroupRepository $groupRepository,
        User $user,
        CreatePortfolioFromGroupInviteHandler $createPortfolioFromGroupInviteHandler,
        PortfoliosQuery $portfoliosQuery
    ) {
        $this->templateRenderer = $templateRenderer;
        $this->session = $session;
        $this->friendRequestsQuery = $friendRequestsQuery;
        $this->friendsListRepository = $friendsListRepository;
        $this->portfolioRepository = $portfolioRepository;
        $this->groupRepository = $groupRepository;
        $this->user = $user;
        $this->createPortfolioFromGroupInviteHandler = $createPortfolioFromGroupInviteHandler;
        $this->portfoliosQuery = $portfoliosQuery;
    }

    public function show() : Response
    {
        if(!$this->user->hasPermission(new CanSeeDashboard())) {
            // TODO - Store the URL in a config file and access it
            return new RedirectResponse('/login');
        }

        $template = 'dashboard/Dashboard.html.twig';

        if(!$this->user instanceof AuthenticatedUser) {
            throw new \LogicException('Only authenticated users can view their dashboards');
        }

        $content = $this->templateRenderer->render(
            $template,
            [
                'nickname' => $this->session->get('nickname'), // TODO - Use RBAC User
                'friendRequests' => $this->friendRequestsQuery->execute(),
                'friendsList' => $this->getFriendsList(),
                'portfolios' => $this->portfoliosQuery->execute($this->user->getId()),
                'groupInvites' => $this->groupRepository->getGroupInvitesForUser($this->session->get('userId')) // TODO - Use RBAC User
            ]
        );

        return new Response($content);
    }

    public function acceptGroupInvite(Request $request)
    {
        $groupId = $request->get('groupId');
        $userId = $this->session->get('userId');

        $responseContent = ['success' => true];
        try {
            if(!$this->user instanceof AuthenticatedUser) {
                throw new \LogicException('Only authenticated users can create portfolio');
            }
            $this->groupRepository->acceptGroupInvite($userId, $groupId);
            $command = new CreatePortfolioFromGroupInvite(
                $groupId,
                $this->user->getId()
            );
            $this->createPortfolioFromGroupInviteHandler->handle($command);
        } catch (\Exception $e) {
            $responseContent['success'] = false;
        }

        $response = new Response();
        $response->setContent(
            json_encode($responseContent)
        );
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function declineGroupInvite(Request $request)
    {
        $groupId = $request->get('groupId');
        $userId = $this->session->get('userId');

        $responseContent = ['success' => true];
        try {
            $this->groupRepository->declineGroupInvite($userId, $groupId);
        } catch (\Exception $e) {
            $responseContent['success'] = false;
        }

        $response = new Response();
        $response->setContent(
            json_encode($responseContent)
        );
        $response->headers->set('Content-Type', 'application/json');

        return $response;
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