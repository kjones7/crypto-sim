<?php declare(strict_types=1);

namespace CryptoSim\Simulation\Domain;

interface TransactionRepository
{
    public function add(Transaction $transaction): void;
}