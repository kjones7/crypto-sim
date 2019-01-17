<?php declare(strict_types=1);

namespace CryptoSim\Framework\Csrf;

/**
 * Class StoredTokenReader
 * Reads stored CSRF tokens
 * @package CryptoSim\Framework\Csrf
 */
final class StoredTokenReader
{
    /** @var TokenStorage */
    private $tokenStorage;

    /**
     * StoredTokenReader constructor.
     * @param TokenStorage $tokenStorage
     */
    public function __construct(TokenStorage $tokenStorage) {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Reads a stored token using a key
     * @param string $key The key used to access the token
     * @return Token The CSRF token
     * @throws \Exception
     */
    public function read(string $key): Token
    {
        $token = $this->tokenStorage->retrieve($key);

        if ($token !== null) {
            return $token;
        }

        $token = Token::generate();
        $this->tokenStorage->store($key, $token);
        
        return $token;
    }
}