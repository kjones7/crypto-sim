<?php declare(strict_types=1);

namespace CryptoSim\User\Domain;

use CryptoSim\User\Application\Friend;

interface FriendsListRepository
{
    public function createFriendFromUserId(string $userId): Friend;
    /**
     * @param string $userId
     * @return string[]
     */
    public function getUserIdsOfFriendsFromUserId(string $userId): array;
}