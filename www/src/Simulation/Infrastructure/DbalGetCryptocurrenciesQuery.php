<?php declare(strict_types=1);

namespace CryptoSim\Simulation\Infrastructure;

use CryptoSim\Simulation\Application\Cryptocurrency;
use CryptoSim\Simulation\Domain\GetCryptocurrenciesQuery;
use Doctrine\DBAL\Connection;
use PDO;

final class DbalGetCryptocurrenciesQuery implements GetCryptocurrenciesQuery
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /** @return Cryptocurrency[] */
    public function execute(): array
    {
        $qb = $this->connection->createQueryBuilder();
        $stmt = $qb
            ->addSelect('c.id')
            ->addSelect('c.name')
            ->addSelect('c.abbreviation')
            ->addSelect('cp.worth_in_USD')
            ->from('cryptocurrencies', 'c')
            ->innerJoin('c', 'cryptocurrency_prices', 'cp', 'c.id = cp.cryptocurrency_id')
            ->where("cp.date_added > (SELECT MAX(date_added) - 5 FROM cryptocurrency_prices)")
            ->execute()
        ;

        $rows = $stmt->fetchAll();
        $cryptocurrencies = [];

        foreach($rows as $row){
            $cryptocurrencies[$row['abbreviation']] = new Cryptocurrency(
                $row['id'],
                $row['name'],
                $row['abbreviation'],
                $row['worth_in_USD']
            );
        }

        return $cryptocurrencies;
    }

    /**
     * @return array
     */
    public function apiExecute(): array
    {
//        $qb = $this->connection->createQueryBuilder();
//        $stmt = $qb
//            ->addSelect('c.id')
//            ->addSelect('c.name')
//            ->addSelect('c.abbreviation')
//            ->addSelect('cp.worth_in_USD')
//            ->from('cryptocurrencies', 'c')
//            ->innerJoin('c', 'cryptocurrency_prices', 'cp', 'c.id = cp.cryptocurrency_id')
//            ->where("cp.date_added = (SELECT MAX(date_added) FROM cryptocurrency_prices)")
//            ->execute()
//        ;

        $stmt = $this->connection->prepare("
            SELECT
                c.id,
                c.name,
                c.abbreviation,
                cp.worth_in_USD,
                (( ( (SELECT worth_in_USD FROM cryptocurrency_prices WHERE cryptocurrency_id = c.id AND date_added > (SELECT MAX(date_added) - 5 FROM cryptocurrency_prices)) - (SELECT worth_in_USD FROM cryptocurrency_prices WHERE cryptocurrency_id = c.id AND date_added = (SELECT MIN(date_added) FROM cryptocurrency_prices)) ) / (SELECT worth_in_USD FROM cryptocurrency_prices WHERE cryptocurrency_id = c.id AND date_added = (SELECT MIN(date_added) FROM cryptocurrency_prices)) ) * 100) AS percent_change
            FROM cryptocurrencies c 
            INNER JOIN cryptocurrency_prices cp
            ON c.id = cp.cryptocurrency_id
            WHERE cp.date_added > (SELECT MAX(date_added) - 5 FROM cryptocurrency_prices)
        ");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $rows;
    }
}