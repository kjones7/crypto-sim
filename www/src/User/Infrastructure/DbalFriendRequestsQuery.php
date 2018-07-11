<?php declare(strict_types=1);

namespace CryptoSim\User\Infrastructure;

use CryptoSim\User\Application\FriendRequest;
use CryptoSim\User\Application\FriendRequestsQuery;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Session\Session;

final class DbalFriendRequestsQuery implements FriendRequestsQuery
{
    private $connection;
    private $session;

    public function __construct(
        Connection $connection,
        Session $session
    ) {
        $this->connection = $connection;
        $this->session = $session;
    }

    public function execute(): array
    {
        $qb = $this->connection->createQueryBuilder();

        $qb
            ->select('u.nickname')
            ->from('users', 'u')
            ->innerJoin(
                'u',
                'friend_requests',
                'fr',
                'fr.to_user_id = ? AND fr.from_user_id = u.id'
            )
            ->orderBy('fr.date_sent', 'DESC')
            ->setParameter(0, $this->session->get('userId'))
        ;

        $stmt = $qb->execute();
        $rows = $stmt->fetchAll();

        $friendRequests = [];
        foreach ($rows as $row) {
            $friendRequests[] = new FriendRequest($row['nickname']);
        }

        return $friendRequests;
    }
}