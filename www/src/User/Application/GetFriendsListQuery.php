<?php declare(strict_types=1);

namespace CryptoSim\User\Application;

interface GetFriendsListQuery
{
    public function execute(string $userId): array;
}