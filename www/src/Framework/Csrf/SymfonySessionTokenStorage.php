<?php declare(strict_types=1);

namespace CryptoSim\Framework\Csrf;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Token storage using Symfony's session
 * @package CryptoSim\Framework\Csrf
 */
final class SymfonySessionTokenStorage implements TokenStorage
{
    /** @var SessionInterface Session of the current user */
    private $session;

    /**
     * SymfonySessionTokenStorage constructor.
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function store(string $key, Token $token): void
    {
        $this->session->set($key, $token->toString());
    }

    /**
     * {@inheritdoc}
     */
    public function retrieve(string $key): ?Token
    {
        $tokenValue = $this->session->get($key);

        if ($tokenValue === null) {
            return null;
        }
        
        return new Token($tokenValue);
    }
}