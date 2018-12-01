<?php declare(strict_types=1);

namespace CryptoSim\Simulation\Application;

interface GroupHasNotReceivedAllResponsesQuery
{
    /**
     * @param string $groupId
     * @return bool
     */
    public function execute(string $groupId): bool;
}