<?php declare(strict_types=1);

namespace CryptoSim\Framework\DbMapping\Portfolios;

use Doctrine\DBAL\Connection;

final class PortfoliosMapper
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string $portfolioId
     * @return string
     * @throws \Doctrine\DBAL\DBALException
     * @throws Exception
     */
    public function getPortfolioWorth(string $portfolioId) : string
    {
        // TODO - To prevent floating point errors, test out doing the math operations out of SQL
        $stmt = $this->connection->prepare("
            SELECT
              CAST(SUM(t.cryptocurrency_amount*cp.worth_in_USD) AS DECIMAL(17,2)) AS crypto_worth,
              p.start_amount
            FROM portfolios p
            LEFT JOIN transactions t ON p.id = t.portfolio_id
            LEFT JOIN cryptocurrencies c on t.cryptocurrency_id = c.id
            INNER JOIN cryptocurrency_prices cp ON cp.cryptocurrency_id = c.id
            WHERE 
              t.portfolio_id = :portfolioId1
              AND t.status = 'active'
              AND cp.date_added > (SELECT MAX(date_added) - 5 FROM cryptocurrency_prices)
         ");
        $stmt->bindParam(':portfolioId1', $portfolioId);
        $stmt->execute();

        $row =$stmt->fetch();

        if(!$row) {
            throw new Exception('Error getting portfolio worth');
        }
        if(!$row['crypto_worth'] && $row['start_amount']) {
            return '0'; // no transactions, but portfolio exists
        }

        return $row['crypto_worth'];
    }
}