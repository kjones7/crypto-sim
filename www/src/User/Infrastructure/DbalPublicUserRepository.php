<?php declare(strict_types=1);

namespace CryptoSim\User\Infrastructure;

use CryptoSim\User\Application\PublicUser;
use CryptoSim\User\Domain\PublicUserRepository;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Session\Session;

final class DbalPublicUserRepository implements PublicUserRepository
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

    // TODO - Use this method instead of using GetPublicUserFromNicknameQuery
    public function getPublicUserFromNickname(string $nickname): ?PublicUser
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select('id')
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
            $rows[0]['id'] // TODO - Use something like fetch instead of fetchAll above
        );
    }

    public function isUserOnFriendsList(string $userId): bool
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select('count(*)')
            ->from('friends')
            ->where('accepted = 1 AND ((to_user_id = :currentUserId AND from_user_id = :userId) OR (to_user_id = :userId2 AND from_user_id = :currentUserId2))')
            ->setParameter(':currentUserId', $this->session->get('userId'))
            ->setParameter(':userId', $userId)
            ->setParameter(':currentUserId2', $this->session->get('userId'))
            ->setParameter(':userId2', $userId)
        ;

        $stmt = $qb->execute();

        return $stmt->fetchColumn() > 0; // Using greater than just in case more than one friend request gets into DB
    }

    public function isFriendRequestAwaitingResponse(string $userId): bool
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select('count(*)')
            ->from('friends')
            ->where('accepted IS NULL AND to_user_id = :userId AND from_user_id = :currentUserId')
            ->setParameter(':currentUserId', $this->session->get('userId'))
            ->setParameter(':userId', $userId)
        ;

        $stmt = $qb->execute();

        return $stmt->fetchColumn() > 0; // Using greater than just in case more than one friend request gets into DB
    }
}