<?php declare(strict_types=1);

namespace CryptoSim\User\Domain;

interface UserRepository
{
    public function add(User $user): void;
}