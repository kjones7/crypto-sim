<?php declare(strict_types=1);

namespace CryptoSim\Portfolio\Application;

use Ramsey\Uuid\UuidInterface;

interface PortfoliosQuery
{
    /**
     * @param UuidInterface $userId User ID of the user whose portfolios are being retrieved
     * @return Portfolio[]
     */
    public function execute(UuidInterface $userId): array;
}