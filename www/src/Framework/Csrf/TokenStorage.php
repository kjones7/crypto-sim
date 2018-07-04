<?php declare(strict_types=1);

namespace CryptoSim\Framework\Csrf;

interface TokenStorage
{
    public function store(string $key, Token $token): void;
    public function retrieve(string $key): ?Token;
}