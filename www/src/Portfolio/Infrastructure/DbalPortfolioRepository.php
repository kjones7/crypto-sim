<?php declare(strict_types=1);

namespace CryptoSim\Portfolio\Infrastructure;

use CryptoSim\Portfolio\Domain\Portfolio;
use CryptoSim\Portfolio\Domain\PortfolioCreatedFromGroupInvite;
use CryptoSim\Portfolio\Domain\PortfolioRepository;
use Doctrine\DBAL\Connection;
use Ramsey\Uuid\Uuid;
use PDO;

final class DbalPortfolioRepository implements PortfolioRepository
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param Portfolio $portfolio
     * @param string $groupId
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Exception
     * @todo Add $groupId to Portfolio class, then remove $groupId param
     */
    public function add(Portfolio $portfolio, ?string $groupId): void
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

    /**
     * @param PortfolioCreatedFromGroupInvite $portfolio
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Exception
     */
    public function addPortfolioFromGroupInvite(PortfolioCreatedFromGroupInvite $portfolio)
    {

        $groupCreatorPortfolioData = $this->getGroupCreatorPortfolioData($portfolio->getGroupId());

        $this->connection->beginTransaction();
        try {
            $qb = $this->connection->createQueryBuilder();

            $qb
                ->insert('portfolios')
                ->values([
                    "id" => $qb->createNamedParameter($portfolio->getId()->toString()),
                    "group_id" => $qb->createNamedParameter($portfolio->getGroupId()),
                    "user_id" => $qb->createNamedParameter($portfolio->getUserId()->toString()),
                    "date_created" => $qb->createNamedParameter(
                        $portfolio->getDateCreated(),
                        'datetime'
                    ),
                    "title" => $qb->createNamedParameter($groupCreatorPortfolioData['title']),
                    "type" => $qb->createNamedParameter($groupCreatorPortfolioData['type']),
                    "start_amount" => $qb->createNamedParameter($groupCreatorPortfolioData['start_amount']),
                    "visibility" => $qb->createNamedParameter($groupCreatorPortfolioData['visibility'])
                ])
                ->execute();

            $this->connection->commit();
        } catch (\Exception $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }

    /**
     * @param string $groupId
     * @return array Contains title, type, start_amount, visibility
     * @throws \Exception
     */
    private function getGroupCreatorPortfolioData(string $groupId)
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->addSelect('p.title')
            ->addSelect('p.type')
            ->addSelect('p.start_amount')
            ->addSelect('p.visibility')
            ->from('portfolios', 'p')
            ->innerJoin(
                'p',
                'groups',
                'g',
                "g.id = {$qb->createNamedParameter($groupId)}"
            )
            ->where('p.user_id = g.creator_user_id')
            ->andWhere("p.group_id = {$qb->createNamedParameter($groupId)}");

        $stmt = $qb->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(count($rows) !== 1) {
            throw new \Exception('Getting group creator portfolio should only return one portfolio');
        }

        return $rows[0];
    }
}