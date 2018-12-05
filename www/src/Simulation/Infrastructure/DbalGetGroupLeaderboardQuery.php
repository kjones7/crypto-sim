<?php declare(strict_types=1);

namespace CryptoSim\Simulation\Infrastructure;

use CryptoSim\Simulation\Application\LeaderboardEntry;
use CryptoSim\Simulation\Domain\GetGroupLeaderboardQuery;
use Doctrine\DBAL\Connection;
use PDO;

final class DbalGetGroupLeaderboardQuery implements GetGroupLeaderboardQuery
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function execute(string $groupId): array
    {
        $stmt = $this->connection->prepare("
            SELECT
              portfolios.title,
              users.nickname,
              CAST(SUM(cp.worth_in_USD * transactions.cryptocurrency_amount) AS DECIMAL(17,2)) As portfolioWorth
            FROM portfolios
            INNER JOIN users ON portfolios.user_id = users.id
            INNER JOIN transactions ON transactions.portfolio_id = portfolios.id
            INNER JOIN cryptocurrency_prices cp ON cp.cryptocurrency_id = transactions.cryptocurrency_id
            WHERE cp.date_added > (SELECT MAX(date_added) - 5 FROM cryptocurrency_prices)
            AND portfolios.group_id = :groupId
            GROUP BY portfolios.title, users.nickname
            ORDER BY portfolioWorth DESC
        ");
        $stmt->bindParam(':groupId', $groupId);
        $stmt->execute();

        $rows =$stmt->fetchAll(PDO::FETCH_ASSOC);
        $leaderboardEntries = [];
        $positionCounter = 1;

        foreach($rows as $row){
            $leaderboardEntries[] = new LeaderboardEntry(
                $positionCounter,
                $row['nickname'],
                $row['portfolioWorth'],
                $row['title']
            );

            $positionCounter += 1;
        }

        return $leaderboardEntries;
    }
}