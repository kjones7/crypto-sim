<?php declare(strict_types=1);

namespace CryptoSim\Framework\Rbac;

use Ramsey\Uuid\Uuid;
use CryptoSim\Framework\Rbac\Role\PortfolioCreator;
use Symfony\Component\HttpFoundation\Session\Session;

final class SymfonySessionCurrentUserFactory
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function create(): User
    {
        if (!$this->session->has('userId')) {
            return new Guest();
        }
        
        return new AuthenticatedUser(
            Uuid::fromString($this->session->get('userId')),
            [new PortfolioCreator()]
        );
    }
}