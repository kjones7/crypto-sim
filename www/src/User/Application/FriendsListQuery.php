<?php declare(strict_types=1);

namespace CryptoSim\User\Application;

interface FriendsListQuery
{
    /** @return Friend[] */
    public function execute(): array;
}