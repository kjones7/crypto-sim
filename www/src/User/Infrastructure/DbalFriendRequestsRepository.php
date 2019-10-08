<?php declare(strict_types=1);

namespace CryptoSim\User\Infrastructure;

use CryptoSim\User\Domain\FriendRequestsRepository;
use Doctrine\DBAL\Connection;
use LogicException;
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
        // TODO - Ensure that friend request doesn't have a response (accepted and date_replied is null)
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
        $currentUserId = $this->session->get('userId');

        $isFriendRequestPendingFromToUser = $this->fetchIfPendingFriendRequestExists($toUserId, $currentUserId);
        if ($isFriendRequestPendingFromToUser) {
            $this->accept($toUserId);
            return;
        }

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
            ->setParameter('fromUserId', $currentUserId);

        $qb->execute();
    }

    private function fetchIfPendingFriendRequestExists(string $fromUserId, string $toUserId) : bool
    {
        $qb = $this->connection->createQueryBuilder();

        // Check if there's already a pending friend request from the $toUserId to the current user
        $qb
            ->select('count(ID)')
            ->from('friends')
            ->where('to_user_id = :toUserId')
            ->andWhere('from_user_id = :fromUserId')
            ->andWhere('accepted IS NULL');

        $qb->setParameter(':fromUserId', $fromUserId);
        $qb->setParameter(':toUserId', $toUserId);
        $qb->execute();

        $stmt = $qb->execute();
        $count = $stmt->fetchColumn();

        if ($count === false) {
            // Count should always at least be 0, false indicates error
            // TODO - Catch this exception in function usages
            throw new LogicException;
        }

        return $count > 0;
    }
}