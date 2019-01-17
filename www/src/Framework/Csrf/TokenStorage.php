<?php declare(strict_types=1);

namespace CryptoSim\Framework\Csrf;

/**
 * CSRF token storage
 * @package CryptoSim\Framework\Csrf
 */
interface TokenStorage
{
    /**
     * Stores a CSRF token
     * @param string $key The key to be used to store/access the CSRF token
     * @param Token $token The CSRF token
     */
    public function store(string $key, Token $token): void;

    /**
     * Retrieves the CSRF token from the current user's session
     * @param string $key The key used to access the CSRF token in the session
     * @return Token|null Returns token if found, null if not found
     */
    public function retrieve(string $key): ?Token;
}