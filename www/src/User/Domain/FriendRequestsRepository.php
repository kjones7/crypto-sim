<?php declare(strict_types=1);

namespace CryptoSim\User\Domain;

interface FriendRequestsRepository
{
    // TODO - change the fromNickname variables to fromUserId
    public function accept(string $fromNickname): void;
    public function decline(string $fromNickname): void;
    public function send(string $toUserId): void;
}