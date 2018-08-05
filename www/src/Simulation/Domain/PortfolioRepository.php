<?php declare(strict_types=1);

namespace CryptoSim\Simulation\Domain;

use CryptoSim\Simulation\Application\Portfolio;

interface PortfolioRepository
{
    public function getPortfolioFromId(string $portfolioId): Portfolio; // TODO - This should probably be a query, not in repository
}