<?php declare(strict_types=1);

namespace CryptoSim\Portfolio\Domain;

interface PortfolioRepository
{
    public function add(Portfolio $portfolio, string $groupId): void;
    public function addPortfolioFromGroupInvite(PortfolioCreatedFromGroupInvite $portfolio);
}