<?php declare(strict_types=1);

namespace CryptoSim\User\Infrastructure;

use CryptoSim\User\Application\FriendsListQuery;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Session\Session;
use CryptoSim\User\Application\Friend;

final class DbalFriendsListQuery implements FriendsListQuery
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

    /** @return Friend[] */
    public function execute(): array
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('u.nickname');
        $qb->from('users', 'u');
        $qb->innerJoin(
            'u',
            'friends',
            'f',
            "f.to_user_id = :currentUserId AND f.from_user_id = u.id AND f.accepted = 1"
        );
        $qb->setParameter(':currentUserId', $this->session->get('userId'));

        $stmt = $qb->execute();
        $rows = $stmt->fetchAll();

        $friendsList = [];
        foreach($rows as $row) {
            $friendsList[] = new Friend($row['nickname']);
        }

        return $friendsList;
    }
}