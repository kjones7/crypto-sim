<?php declare(strict_types=1);

namespace CryptoSim\Portfolio\Infrastructure;

use CryptoSim\Portfolio\Domain\GroupRepository;
use CryptoSim\Portfolio\Domain\Portfolio;
use CryptoSim\Portfolio\Domain\PortfolioRepository;
use Doctrine\DBAL\Connection;
use PDO;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;
use CryptoSim\Portfolio\Application\GroupInvite;

final class DbalGroupRepository implements GroupRepository
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function create(Portfolio $portfolio): string
    {
        $id = Uuid::uuid4()->toString();

        $qb = $this->connection->createQueryBuilder();
        $qb
            ->insert('groups')
            ->values([
                'id' => $qb->createNamedParameter($id),
                'creator_user_id' => $qb->createNamedParameter($portfolio->getUserId()->toString())
            ])
            ->execute();

        return $id;
    }

    /**
     * Gets the group invites for the specified user
     * @param string $userId - Get the group invites of this user
     * @return GroupInvite[]
     */
    public function getGroupInvitesForUser(string $userId): array
    {
        $qb = $this->connection->createQueryBuilder();

        $qb
            ->addSelect('gi.id')
            ->addSelect('gi.to_user_id')
            ->addSelect('gi.group_id')
            ->addSelect('g.creator_user_id')
            ->addSelect('u.nickname')
            ->from('group_invites', 'gi')
            ->innerJoin(
                'g',
                'users',
                'u',
                'u.id = g.creator_user_id'
            )
            ->innerJoin(
                'gi',
                'groups',
                'g',
                'g.id = gi.group_id'
            )
            ->where("gi.to_user_id = {$qb->createNamedParameter($userId)}")
            ->andWhere("gi.accepted IS NULL")
        ;

        $stmt = $qb->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $groupInvites = [];
        foreach($rows as $row) {
            $groupInvites[] = new GroupInvite(
                $row['id'],
                $row['to_user_id'],
                $row['creator_user_id'],
                $row['group_id'],
                $row['nickname']
            );
        }

        return $groupInvites;
    }

    public function acceptGroupInvite(string $userId, string $groupId)
    {
        $qb = $this->connection->createQueryBuilder();
        $this->connection->beginTransaction();

        try {
            $qb
                ->update('group_invites', 'gi')
                ->set('accepted', '1')
                ->where("to_user_id = {$qb->createNamedParameter($userId)}")
                ->where("group_id = {$qb->createNamedParameter($groupId)}")
            ;

            $qb->execute();
            $this->connection->commit();

        } catch (\Exception $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }

    public function declineGroupInvite(string $userId, string $groupId)
    {
        $qb = $this->connection->createQueryBuilder();
        $this->connection->beginTransaction();

        try {
            $qb
                ->update('group_invites', 'gi')
                ->set('accepted', '0')
                ->where("to_user_id = {$qb->createNamedParameter($userId)}")
                ->where("group_id = {$qb->createNamedParameter($groupId)}")
            ;

            $qb->execute();
            $this->connection->commit();

        } catch (\Exception $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }
}