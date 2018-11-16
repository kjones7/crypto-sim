<?php declare(strict_types=1);

namespace CryptoSim\Portfolio\Application;

use CryptoSim\Portfolio\Domain\GroupRepository;
use CryptoSim\Portfolio\Domain\Portfolio;
use CryptoSim\Portfolio\Domain\PortfolioRepository;

final class CreatePortfolioHandler
{
    private $portfolioRepository;
    private $groupRepository;

    public function __construct(
        PortfolioRepository $portfolioRepository,
        GroupRepository $groupRepository
    )
    {
        $this->portfolioRepository = $portfolioRepository;
        $this->groupRepository = $groupRepository;
    }

    public function handle(CreatePortfolio $command): void
    {
        $groupId = null;

        $portfolio = Portfolio::create(
            $command->getUserId(),
            $command->getTitle(),
            $command->getType(),
            $command->getVisibility(),
            $command->getGroupInviteUserIds()
        );

        if($command->getType() == 'group') {
            $groupId = $this->groupRepository->create($portfolio);
        }
        $this->portfolioRepository->add($portfolio, $groupId);
    }
}