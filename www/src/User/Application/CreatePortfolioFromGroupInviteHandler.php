<?php declare (strict_types=1);
/**
 * Created by PhpStorm.
 * User: Kyle
 * Date: 11/28/2018
 * Time: 6:12 PM
 */

namespace CryptoSim\User\Application;

use CryptoSim\Portfolio\Domain\PortfolioCreatedFromGroupInvite;
use CryptoSim\Portfolio\Domain\PortfolioRepository;

class CreatePortfolioFromGroupInviteHandler
{
    private $portfolioRepository;

    public function __construct(PortfolioRepository $portfolioRepository)
    {
        $this->portfolioRepository = $portfolioRepository;
    }

    public function handle(CreatePortfolioFromGroupInvite $command)
    {
        $portfolio = PortfolioCreatedFromGroupInvite::create(
            $command->getUserId(),
            $command->getGroupId()
        );
        $this->portfolioRepository->addPortfolioFromGroupInvite($portfolio);
    }
}