<?php declare(strict_types=1);

namespace CryptoSim\Simulation\Infrastructure;

use CryptoSim\Simulation\Application\Cryptocurrency;
use CryptoSim\Simulation\Application\LeaderboardEntry;
use CryptoSim\Simulation\Domain\GetCryptocurrenciesQuery;
use CryptoSim\Simulation\Domain\GetLeaderboardQuery;
use Doctrine\DBAL\Connection;
use PDO;

final class DbalGetLeadeboardQuery implements GetLeaderboardQuery
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /** @return LeaderboardEntry[] */
    public function execute(): array
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
            GROUP BY portfolios.title, users.nickname
            ORDER BY portfolioWorth DESC
        ");
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

    /**
     * @return array
     */
    public function apiExecute(): array
    {
        $qb = $this->connection->createQueryBuilder();
        $stmt = $qb
            ->addSelect('id')
            ->addSelect('name')
            ->addSelect('abbreviation')
            ->addSelect('worth_in_USD')
            ->from('cryptocurrencies')
            ->execute()
        ;

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $rows;
    }
}