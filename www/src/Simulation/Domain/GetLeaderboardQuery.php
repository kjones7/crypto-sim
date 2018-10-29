<?php declare(strict_types=1);

namespace CryptoSim\Simulation\Domain;

use CryptoSim\Simulation\Application\LeaderboardEntry;

interface GetLeaderboardQuery
{
    /** @return LeaderboardEntry[] */
    public function execute(): array;
}