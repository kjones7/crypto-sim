<?php declare(strict_types=1);

namespace CryptoSim\User\Application;

interface FriendRequestsQuery
{
    /** @return FriendRequest[] */
    public function execute(): array;
}