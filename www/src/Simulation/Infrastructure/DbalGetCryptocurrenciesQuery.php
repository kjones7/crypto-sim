<?php declare(strict_types=1);

namespace CryptoSim\Simulation\Infrastructure;

use CryptoSim\Simulation\Application\Cryptocurrency;
use CryptoSim\Simulation\Domain\GetCryptocurrenciesQuery;
use Doctrine\DBAL\Connection;

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
            ->addSelect('id')
            ->addSelect('name')
            ->addSelect('abbreviation')
            ->addSelect('worth_in_USD')
            ->from('cryptocurrencies')
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
}