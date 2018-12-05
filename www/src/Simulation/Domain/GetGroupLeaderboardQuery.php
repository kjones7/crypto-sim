<?php declare(strict_types=1);

namespace CryptoSim\Simulation\Domain;

use CryptoSim\Simulation\Application\LeaderboardEntry;

interface GetGroupLeaderboardQuery
{
    /** @return LeaderboardEntry[] */
    public function execute(string $groupId): array;
}