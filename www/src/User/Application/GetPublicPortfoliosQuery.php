<?php declare(strict_types=1);

namespace CryptoSim\User\Application;

interface GetPublicPortfoliosQuery
{
    /**
     * @param string $userId
     * @return Portfolio[]
     */
    public function execute(string $userId): array;
}