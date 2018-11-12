<?php declare(strict_types=1);

namespace CryptoSim\Portfolio\Infrastructure;

use CryptoSim\Portfolio\Domain\GroupRepository;
use CryptoSim\Portfolio\Domain\Portfolio;
use CryptoSim\Portfolio\Domain\PortfolioRepository;
use Doctrine\DBAL\Connection;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

final class DbalGroupRepository implements GroupRepository
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function create(): string
    {
        $id = Uuid::uuid4()->toString();

        $qb = $this->connection->createQueryBuilder();
        $qb
            ->insert('groups')
            ->values([
                'id' => $qb->createNamedParameter($id)
            ])
            ->execute();

        return $id;
    }
}