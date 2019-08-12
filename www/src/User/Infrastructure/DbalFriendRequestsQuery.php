<?php declare(strict_types=1);

namespace CryptoSim\User\Infrastructure;

use CryptoSim\User\Application\FriendRequest;
use CryptoSim\User\Application\FriendRequestsQuery;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class DbalFriendRequestsQuery implements FriendRequestsQuery
{
    private $connection;
    private $session;

    public function __construct(
        Connection $connection,
        SessionInterface $session
    ) {
        $this->connection = $connection;
        $this->session = $session;
    }

    public function execute(): array
    {
        $qb = $this->connection->createQueryBuilder();

        $qb
            ->select('u.nickname, f.from_user_id')
            ->from('users', 'u')
            ->innerJoin(
                'u',
                'friends',
                'f',
                'f.to_user_id = ? AND f.from_user_id = u.id'
            )
            ->where('accepted IS NULL')
            ->orderBy('f.date_sent', 'DESC')
            ->setParameter(0, $this->session->get('userId'))
        ;

        $stmt = $qb->execute();
        $rows = $stmt->fetchAll();

        $friendRequests = [];
        foreach ($rows as $row) {
            $friendRequests[] = new FriendRequest(
                $row['nickname'],
                $row['from_user_id']
            );
        }

        return $friendRequests;
    }
}