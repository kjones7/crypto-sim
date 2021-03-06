<?php declare(strict_types=1);

namespace CryptoSim\Portfolio\Presentation;

use CryptoSim\Framework\Rbac\Permission\CreatePortfolio;
use CryptoSim\Framework\Rbac\User;
use CryptoSim\Framework\Rendering\TemplateRenderer;
use CryptoSim\Framework\Rbac\AuthenticatedUser;
use CryptoSim\Portfolio\Application\CreatePortfolioHandler;
use CryptoSim\Portfolio\Domain\GroupRepository;
use CryptoSim\User\Application\GetFriendsListQuery;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class PortfolioController
{
    private $templateRenderer;
    private $user;
    private $session;
    private $createPortfolioFormFactory;
    private $createPortfolioHandler;
    private $getFriendsListQuery;
    private $groupRepository;

    public function __construct(
        TemplateRenderer $templateRenderer,
        User $user,
        SessionInterface $session,
        CreatePortfolioFormFactory $createPortfolioFormFactory,
        CreatePortfolioHandler $createPortfolioHandler,
        GetFriendsListQuery $getFriendsListQuery,
        GroupRepository $groupRepository
    ){
        $this->templateRenderer = $templateRenderer;
        $this->user = $user;
        $this->session = $session;
        $this->createPortfolioFormFactory = $createPortfolioFormFactory;
        $this->createPortfolioHandler = $createPortfolioHandler;
        $this->getFriendsListQuery = $getFriendsListQuery;
        $this->groupRepository = $groupRepository;
    }

    public function show(): Response
    {
        $friends = $this->getFriendsListQuery->execute($this->session->get('userId'));
        $groupInvites = $this->groupRepository->getGroupInvitesForUser($this->session->get('userId'));
        $content = $this->templateRenderer->render('CreatePortfolio.html.twig', [
            'friends' => $friends,
            'groupInvites' => $groupInvites
        ]);
        return new Response($content);
    }

    public function create(Request $request): Response
    {
        if(!$this->user->hasPermission(new CreatePortfolio())) {
            $this->session->getFlashBag()->add(
                'errors',
                'You must be logged to create a portfolio.'
            );
            return new RedirectResponse('/login');
        }

        $response = new RedirectResponse('/dashboard');

        $form = $this->createPortfolioFormFactory->createFromRequest($request);

        if($form->hasValidationErrors()) {
            foreach ($form->getValidationErrors() as $errorMessage) {
                $this->session->getFlashBag()->add('errors', $errorMessage);
            }
            return $response;
        }

        if(!$this->user instanceof AuthenticatedUser) {
            throw new \LogicException('Only authenticated users can create portfolio');
        }
        $this->createPortfolioHandler->handle($form->toCommand($this->user));

        $this->session->getFlashBag()->add(
            'success',
            'Your portfolio was created successfully'
        );
        return $response;
    }
}
