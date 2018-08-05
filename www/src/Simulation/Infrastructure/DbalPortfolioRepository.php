<?php declare(strict_types=1);

namespace CryptoSim\Simulation\Infrastructure;

use CryptoSim\Simulation\Application\Portfolio;
use CryptoSim\Simulation\Domain\PortfolioRepository;
use Doctrine\DBAL\Connection;

final class DbalPortfolioRepository implements PortfolioRepository
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getPortfolioFromId(string $portfolioId): Portfolio
    {
        $qb = $this->connection->createQueryBuilder();
        $stmt = $qb
            ->addSelect('id')
            ->addSelect('title')
            ->from('portfolios')
            ->where("id = {$qb->createNamedParameter($portfolioId)}")
            ->execute()
        ;

        $row = $stmt->fetch();

        if(!$row) {
            return null;
        }

        return new Portfolio(
            $row['id'],
            $row['title']
        );
    }
}