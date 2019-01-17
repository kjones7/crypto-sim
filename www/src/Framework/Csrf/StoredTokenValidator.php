<?php declare(strict_types=1);

namespace CryptoSim\Framework\Csrf;

/**
 * Class StoredTokenValidator
 * @package CryptoSim\Framework\Csrf
 */
final class StoredTokenValidator
{
    /** @var TokenStorage */
    private $tokenStorage;

    /**
     * StoredTokenValidator constructor.
     * @param TokenStorage $tokenStorage
     */
    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Validates a CSRF token by checking if it exists in storage
     * @param string $key The key used to access the token in storage
     * @param Token $token The token to check if it exists in storage
     * @return bool True if valid, false if invalid
     */
    public function validate(string $key, Token $token): bool
    {
        $storedToken = $this->tokenStorage->retrieve($key);

        if ($storedToken === null) {
            return false;
        }

        return $token->equals($storedToken);
    }
}