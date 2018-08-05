<?php declare(strict_types=1);

namespace CryptoSim\Simulation\Domain;

use CryptoSim\Simulation\Application\Cryptocurrency;

interface GetCryptocurrenciesQuery
{
    /** @return Cryptocurrency[] */
    public function execute(): array;
}