<?php declare(strict_types=1);

namespace CryptoSim\Portfolio\Application;

use CryptoSim\Portfolio\Domain\Portfolio;
use CryptoSim\Portfolio\Domain\PortfolioRepository;

final class CreatePortfolioHandler
{
    private $portfolioRepository;

    public function __construct(PortfolioRepository $portfolioRepository)
    {
        $this->portfolioRepository = $portfolioRepository;
    }

    public function handle(CreatePortfolio $command): void
    {
        $portfolio = Portfolio::create(
            $command->getUserId(),
            $command->getTitle(),
            $command->getType(),
            $command->getVisibility()
        );
        $this->portfolioRepository->add($portfolio);
    }
}