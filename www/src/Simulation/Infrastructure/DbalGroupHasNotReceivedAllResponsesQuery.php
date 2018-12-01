<?php declare(strict_types=1);

namespace CryptoSim\Simulation\Infrastructure;

use CryptoSim\Simulation\Application\GroupHasNotReceivedAllResponsesQuery;
use Doctrine\DBAL\Connection;
use PDO;

final class DbalGroupHasNotReceivedAllResponsesQuery implements GroupHasNotReceivedAllResponsesQuery
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string $groupId
     * @return bool
     */
    public function execute(string $groupId): bool
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select('count(*)')
            ->from('group_invites')
            ->where("group_id = {$qb->createNamedParameter($groupId)}")
            ->andWhere('accepted IS NULL')
        ;

        $stmt = $qb->execute();

        return (bool)$stmt->fetchColumn();
    }
}