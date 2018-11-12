<?php declare(strict_types=1);

namespace CryptoSim\User\Infrastructure;

use CryptoSim\User\Application\Friend;
use CryptoSim\User\Domain\FriendsListRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Statement;
use Symfony\Component\HttpFoundation\Session\Session;

final class DbalFriendsListRepository implements FriendsListRepository
{
    private $connection;
    private $session;

    public function __construct(
        Connection $connection,
        Session $session
    ){
        $this->connection = $connection;
        $this->session = $session;
    }

    public function createFriendFromUserId(string $userId): Friend
    {
        $qb = $this->connection->createQueryBuilder();

        $qb
            ->select('nickname')
            ->from('users')
            ->where("id = {$qb->createNamedParameter($userId)}")
        ;
        $stmt = $qb->execute();

        $nickname = $stmt->fetch()['nickname'];
        return new Friend($nickname, $userId);
    }

    /**
     * @param string $userId
     * @return string[]
     */
    public function getUserIdsOfFriendsFromUserId(string $userId): array
    {
        $currentUserId = $this->session->get('userId');
        /** @var Statement $stmt */
        $stmt = $this->connection->prepare("
            (SELECT to_user_id from friends WHERE from_user_id = ? AND accepted = 1)
            UNION
            (SELECT from_user_id from friends WHERE to_user_id = ? AND accepted = 1)
        ");
        $stmt->bindParam(1, $currentUserId);
        $stmt->bindParam(2, $currentUserId);
        $stmt->execute();

        $rows = $stmt->fetchAll();

        $userIdsOfFriends = [];
        foreach($rows as $row) {
            $userIdsOfFriends[] = $row['to_user_id'];
        }

        return $userIdsOfFriends;
    }
}