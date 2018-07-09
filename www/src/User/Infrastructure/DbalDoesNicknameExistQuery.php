<?php declare(strict_types=1);

namespace CryptoSim\User\Infrastructure;

use Doctrine\DBAL\Connection;
use CryptoSim\User\Application\DoesNicknameExistQuery;

final class DbalDoesNicknameExistQuery implements DoesNicknameExistQuery
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function execute(string $nickname): bool
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('count(*)');
        $qb->from('users');
        $qb->where("nickname = {$qb->createNamedParameter($nickname)}");
        $qb->execute();

        $stmt = $qb->execute();
        return (bool)$stmt->fetchColumn();
    }
}