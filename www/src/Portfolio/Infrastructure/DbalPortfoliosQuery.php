<?php declare(strict_types=1);

namespace CryptoSim\Portfolio\Infrastructure;

use CryptoSim\Portfolio\Application\Portfolio;
use CryptoSim\Portfolio\Application\PortfoliosQuery;
use Doctrine\DBAL\Connection;
use Ramsey\Uuid\UuidInterface;

final class DbalPortfoliosQuery implements PortfoliosQuery
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param UuidInterface $userId
     * @return Portfolio[]
     */
    public function execute(UuidInterface $userId): array
    {
        $qb = $this->connection->createQueryBuilder();
        $stmt = $qb
            ->addSelect('id')
            ->addSelect('type')
            ->addSelect('title')
            ->addSelect('visibility')
            ->addSelect('status')
            ->from('portfolios')
            ->where("user_id = {$qb->createNamedParameter($userId->toString())}")
            ->execute()
        ;

        $rows = $stmt->fetchAll();

        $portfolios = [];
        foreach ($rows as $row){
            $portfolios[] = new Portfolio(
                $row['id'],
                $row['type'],
                $row['title'],
                $row['visibility'],
                $row['status']
            );
        }

        return $portfolios;
    }
}