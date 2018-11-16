<?php declare(strict_types=1);

namespace CryptoSim\Portfolio\Infrastructure;

use CryptoSim\Portfolio\Domain\Portfolio;
use CryptoSim\Portfolio\Domain\PortfolioRepository;
use Doctrine\DBAL\Connection;
use Ramsey\Uuid\Uuid;

final class DbalPortfolioRepository implements PortfolioRepository
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function add(Portfolio $portfolio, string $groupId): void
    {
        $this->connection->beginTransaction();
        try {
            // Add portfolio to database
            $qb = $this->connection->createQueryBuilder();
            $qb
                ->insert('portfolios')
                ->values([
                    'id' => $qb->createNamedParameter($portfolio->getId()->toString()),
                    'group_id' => $qb->createNamedParameter($groupId),
                    'title' => $qb->createNamedParameter($portfolio->getTitle()),
                    'type' => $qb->createNamedParameter($portfolio->getType()),
                    'visibility' => $qb->createNamedParameter($portfolio->getVisibility()),
                    'user_id' => $qb->createNamedParameter($portfolio->getUserId()->toString()),
                    'date_created' => $qb->createNamedParameter(
                        $portfolio->getDateCreated(),
                        'datetime'
                    )
                ])
                ->execute();

            // Add group invites to database
            $this->addGroupInvites($portfolio->getGroupInviteUserIds(), $groupId);

            $this->connection->commit();
        } catch (\Exception $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }

    // TODO - Instead of generating UUID here, do that in a domain object
    private function addGroupInvites($groupInviteUserIds, $groupId) {
        $qb = $this->connection->createQueryBuilder();
        foreach($groupInviteUserIds as $groupInviteUserId) {
            $id = uuid::uuid4()->toString();
            $qb->insert('group_invites');
            $qb->values([
                'id' => $qb->createNamedParameter($id),
                'to_user_id' => $qb->createNamedParameter($groupInviteUserId),
                'group_id' => $qb->createNamedParameter($groupId)
            ]);
            $qb->execute();
        }
    }

    /** @returns Portfolio[] */
    public function getPortfoliosFromUserId(string $userId): array
    {
        $qb = $this->connection->createQueryBuilder();
        $stmt = $qb
            ->addSelect('id')
            ->addSelect('type')
            ->addSelect('title')
            ->addSelect('visibility')
            ->addSelect('status')
            ->from('portfolios')
            ->where("user_id = {$qb->createNamedParameter($userId)}")
            ->execute()
        ;

        $rows = $stmt->fetchAll();

        $portfolios = [];
        foreach ($rows as $row){
            $portfolios[] = new \CryptoSim\Portfolio\Application\Portfolio(
                $row['id'],
                $row['type'],
                $row['title'],
                $row['visibility'],
                $row['status']
            );
        }

        return $portfolios;
    }
}