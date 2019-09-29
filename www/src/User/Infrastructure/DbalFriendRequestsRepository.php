<?php declare(strict_types=1);

namespace CryptoSim\User\Infrastructure;

use CryptoSim\User\Domain\FriendRequestsRepository;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class DbalFriendRequestsRepository implements FriendRequestsRepository
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

    public function accept(string $fromUserId): void
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->update('friends', 'f')
            ->set('f.accepted', 1)
            ->set('f.date_replied', 'CURRENT_TIME')
            ->where('f.to_user_id = :currentUserId')
            ->andWhere('f.from_user_id = :fromUserId')
            ->andWhere('date_replied IS NULL') // Can only accept requests that don't have any replies
            ->andWhere('accepted IS NULL') // Can only accept requests that have not been accepted/declined
            ->setParameter(':currentUserId', $this->session->get('userId'))
            ->setParameter(':fromUserId', $fromUserId)
        ;

        $qb->execute();
    }

    public function decline(string $fromUserId): void
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->update('friends', 'f')
            ->set('f.accepted', 0)
            ->set('f.date_replied', 'CURRENT_TIME')
            ->where('f.to_user_id = :currentUserId AND f.from_user_id = :fromUserId')
            ->setParameter(':currentUserId', $this->session->get('userId'))
            ->setParameter(':fromUserId', $fromUserId)
        ;

        $qb->execute();
    }

    public function send(string $toUserId): void
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->insert('friends')
            ->values(
                array(
                    'id' => 'UUID()',
                    'to_user_id' => ':toUserId',
                    'from_user_id' => ':fromUserId',
                    'date_sent' => 'NOW()'
                )
            )
            ->setParameter('toUserId', $toUserId)
            ->setParameter('fromUserId', $this->session->get('userId'));

        $qb->execute();
    }
}