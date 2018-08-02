<?php declare(strict_types=1);

namespace CryptoSim\Portfolio\Presentation;

use CryptoSim\Framework\Rbac\Permission\CreatePortfolio;
use CryptoSim\Framework\Rbac\User;
use CryptoSim\Framework\Rendering\TemplateRenderer;
use CryptoSim\Framework\Rbac\AuthenticatedUser;
use CryptoSim\Portfolio\Application\CreatePortfolioHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

final class PortfolioController
{
    private $templateRenderer;
    private $user;
    private $session;
    private $createPortfolioFormFactory;
    private $createPortfolioHandler;

    public function __construct(
        TemplateRenderer $templateRenderer,
        User $user,
        Session $session,
        CreatePortfolioFormFactory $createPortfolioFormFactory,
        CreatePortfolioHandler $createPortfolioHandler
    ){
        $this->templateRenderer = $templateRenderer;
        $this->user = $user;
        $this->session = $session;
        $this->createPortfolioFormFactory = $createPortfolioFormFactory;
        $this->createPortfolioHandler = $createPortfolioHandler;
    }

    public function show(): Response
    {
        $content = $this->templateRenderer->render('CreatePortfolio.html.twig');
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

        $response = new RedirectResponse('/portfolios/create');
        $form = $this->createPortfolioFormFactory->createFromRequest($request);

        if($form->hasValidationErrors()) {
            foreach ($form->getValidationErrors() as $errorMessage) {
                $this->session->getFlashBag()->add('errors', $errorMessage);
            }
            return $response;
        }

        if(!$this->user instanceof AuthenticatedUser) {
            throw new \LogicException('Only authenticated users can submit links');
        }
        $this->createPortfolioHandler->handle($form->toCommand($this->user));

        $this->session->getFlashBag()->add(
            'success',
            'Your portfolio was created successfully'
        );
        return $response;
    }
}