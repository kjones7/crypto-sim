<?php declare(strict_types=1);

namespace CryptoSim\User\Infrastructure;

use CryptoSim\User\Application\GetPublicUserFromNicknameQuery;
use CryptoSim\User\Application\PublicUser;
use Doctrine\DBAL\Connection;

final class DbalGetPublicUserFromNicknameQuery implements GetPublicUserFromNicknameQuery
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function execute(string $nickname): PublicUser
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select('id, country')
            ->from('users')
            ->where('nickname = :nickname')
            ->setParameter(':nickname', $nickname)
        ;

        $stmt = $qb->execute();
        $rows = $stmt->fetchAll();

        if(count($rows) > 1) {
            // TODO - handle this exception
            throw new \Exception('Only one result expected');
        }

        return new PublicUser(
            $nickname,
            $rows[0]['id'],  // TODO - Use something like fetch instead of fetchAll above
            $rows[0]['country']
        );
    }
}