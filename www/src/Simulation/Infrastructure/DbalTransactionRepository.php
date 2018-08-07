<?php declare(strict_types=1);

namespace CryptoSim\Simulation\Infrastructure;

use Doctrine\DBAL\Types\Type;
use CryptoSim\Simulation\Domain\Transaction;
use CryptoSim\Simulation\Domain\TransactionRepository;
use Doctrine\DBAL\Connection;

final class DbalTransactionRepository implements TransactionRepository
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }


    public function save(Transaction $transaction): void
    {
        $cryptocurrencyAmount = $this->calculateCryptocurrencyAmountFromIdAndUSD(
            $transaction->getCryptocurrencyId(),
            $transaction->getUSDAmount()
        );
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->insert('transactions')
            ->values([
                'id' => $qb->createNamedParameter($transaction->getId()),
                'type' => $qb->createNamedParameter($transaction->getType()),
                'cryptocurrency_amount' => $qb->createNamedParameter($cryptocurrencyAmount),
                'cryptocurrency_id' => $qb->createNamedParameter($transaction->getCryptocurrencyId()),
                'date' => $qb->createNamedParameter(
                    $transaction->getDate(),
                    Type::DATETIME
                ),
                'portfolio_id' => $qb->createNamedParameter($transaction->getPortfolioId()),
                'usd_amount' => $qb->createNamedParameter($transaction->getUSDAmount())
            ])
            ->execute()
        ;
    }

    private function calculateCryptocurrencyAmountFromIdAndUSD(
        int $cryptocurrencyId,
        string $USDAmount
    ): ?string {
        $qb = $this->connection->createQueryBuilder();
        $stmt = $qb
            ->addSelect('worth_in_usd')
            ->from('cryptocurrencies')
            ->where("id = {$qb->createNamedParameter($cryptocurrencyId)}")
            ->execute()
        ;

        $row = $stmt->fetch();

        // TODO - Add error handling for whenever this returns null
        if(!$row){
            return null;
        }

        // TODO - Download a library to handle float mathemtical operations accurately
        $cryptocurrencyAmount = (float)$USDAmount / $row['worth_in_usd'];

        return (string)$cryptocurrencyAmount;
    }
}