<?php declare(strict_types=1);

namespace CryptoSim\Framework\Csrf;

/**
 * Represents a CSRF token
 *
 * @package CryptoSim\Framework\Csrf
 */
final class Token
{
    /** @var string The CSRF token */
    private $token;

    /**
     * Token constructor.
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * @return string The CSRF token as a string
     */
    public function toString(): string
    {
        return $this->token;
    }

    /**
     * Generates a CSRF token
     * @return Token The CSRF token
     * @throws \Exception
     */
    public static function generate(): Token
    {
        $token = bin2hex(random_bytes(256));
        return new Token($token);
    }

    /**
     * Checks if two tokens are equal to each other
     * @param Token $token A token to check equality with
     * @return bool
     */
    public function equals(Token $token): bool
    {
        return ($this->token === $token->toString());
    }
}