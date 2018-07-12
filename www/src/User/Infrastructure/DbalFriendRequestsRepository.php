<?php declare(strict_types=1);

namespace CryptoSim\User\Infrastructure;

use CryptoSim\User\Domain\FriendRequestsRepository;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Session\Session;

final class DbalFriendRequestsRepository implements FriendRequestsRepository
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

    public function accept(string $fromNickname): void
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->update('friends', 'f')
            ->set('f.accepted', 1)
            ->where('f.to_user_id = :currentUserId')
            ->setParameter(':currentUserId', $this->session->get('userId'))
        ;

        $qb->execute();
    }

    public function reject(string $fromNickname): void
    {
        // TODO: Implement reject() method.
    }
}