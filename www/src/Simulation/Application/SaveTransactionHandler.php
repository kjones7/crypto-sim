<?php declare(strict_types=1);
// command handler
namespace CryptoSim\Simulation\Application;

use CryptoSim\Simulation\Domain\Transaction;
use CryptoSim\Simulation\Domain\TransactionRepository;

final class SaveTransactionHandler
{
    private $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function handle(SaveTransaction $command)
    {
        $transaction = Transaction::save(
            $command->getPortfolioId(),
            $command->getCryptocurrencyId(),
            $command->getUSDAmount(),
            $command->getType()
        );

        $this->transactionRepository->add($transaction);
    }
}