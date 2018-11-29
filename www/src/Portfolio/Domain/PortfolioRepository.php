<?php declare(strict_types=1);

namespace CryptoSim\Portfolio\Domain;

interface PortfolioRepository
{
    public function add(Portfolio $portfolio, string $groupId): void;
    /** @returns Portfolio[] */
    public function getPortfoliosFromUserId(string $userId): array; // TODO - This should probably be a query, not in repository
    public function addPortfolioFromGroupInvite(PortfolioCreatedFromGroupInvite $portfolio);
}