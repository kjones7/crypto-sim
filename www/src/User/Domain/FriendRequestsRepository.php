<?php declare(strict_types=1);

namespace CryptoSim\User\Domain;

// TODO - Maybe change the name of this to FriendRequestsActionsRepository
interface FriendRequestsRepository
{
    // TODO - change the fromNickname variables to fromUserId
    public function accept(string $fromNickname): void;
    public function decline(string $fromNickname): void;
    public function send(string $toUserId): void; // TODO - Add validation that will check to make sure a friend request entry doesn't already exist
}