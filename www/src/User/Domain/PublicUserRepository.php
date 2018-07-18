<?php declare(strict_types=1);

namespace CryptoSim\User\Domain;

use CryptoSim\User\Application\PublicUser;

interface PublicUserRepository
{
    public function getPublicUserFromNickname(string $nickname): ?PublicUser;
    public function isUserOnFriendsList(string $userId): bool;
    public function isFriendRequestAwaitingResponse(string $userId): bool;
}