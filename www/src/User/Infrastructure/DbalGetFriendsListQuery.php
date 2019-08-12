<?php declare(strict_types=1);

namespace CryptoSim\User\Infrastructure;

use CryptoSim\User\Application\Friend;
use CryptoSim\User\Application\GetFriendsListQuery;
use CryptoSim\User\Domain\FriendsListRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Statement;
use PDO;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class DbalGetFriendsListQuery implements GetFriendsListQuery
{
    private $connection;
    private $session;

    public function __construct(
        Connection $connection,
        SessionInterface $session
    ){
        $this->connection = $connection;
        $this->session = $session;
    }

    /**
     * Gets the friends list of a user
     * @param int $userId
     * @return Friend[]
     */
    public function execute(string $userId): array
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select('DISTINCT u.nickname, u.id')
            ->from('friends', 'f')
            ->where("f.to_user_id = {$qb->createNamedParameter($userId)}")
            ->orWhere("f.from_user_id = {$qb->createNamedParameter($userId)}")
            ->innerJoin('f', 'users', 'u', 'f.to_user_id = u.id OR f.from_user_id = u.id')
            ->where("u.id != {$qb->createNamedParameter($userId)}")
        ;

        $stmt = $qb->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $friends = [];

        foreach($rows as $row) {
            $friends[] = new Friend(
                $row['nickname'],
                $row['id']
            );
        }

        return $friends;
    }
}