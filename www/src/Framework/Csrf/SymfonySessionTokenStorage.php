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
     * Stores the CSRF token in the session using a key
     * @param string $key The key to be used to store/access the CSRF token
     * @param Token $token The CSRF token
     */
    public function store(string $key, Token $token): void
    {
        $this->session->set($key, $token->toString());
    }

    /**
     * Retrieves the CSRF token from the current user's session
     * @param string $key The key used to access the CSRF token in the session
     * @return Token|null Returns token if found, null if not found
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