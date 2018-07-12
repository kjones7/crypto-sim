<?php declare(strict_types=1);

namespace CryptoSim\User\Domain;

interface FriendRequestsRepository
{
    public function accept(string $fromNickname): void;
    public function reject(string $fromNickname): void;
}