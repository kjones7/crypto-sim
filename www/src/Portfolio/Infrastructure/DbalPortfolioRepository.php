<?php declare(strict_types=1);

namespace CryptoSim\Portfolio\Infrastructure;

use CryptoSim\Portfolio\Domain\Portfolio;
use CryptoSim\Portfolio\Domain\PortfolioRepository;
use Doctrine\DBAL\Connection;

final class DbalPortfolioRepository implements PortfolioRepository
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function add(Portfolio $portfolio): void
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->insert('portfolios')
            ->values([
                'id' => $qb->createNamedParameter($portfolio->getId()->toString()),
                'title' => $qb->createNamedParameter($portfolio->getTitle()),
                'type' => $qb->createNamedParameter($portfolio->getType()),
                'visibility' => $qb->createNamedParameter($portfolio->getVisibility()),
                'user_id' => $qb->createNamedParameter($portfolio->getUserId()->toString()),
                'date_created' => $qb->createNamedParameter(
                    $portfolio->getDateCreated(),
                    'datetime'
                )
            ])
            ->execute();
    }
}