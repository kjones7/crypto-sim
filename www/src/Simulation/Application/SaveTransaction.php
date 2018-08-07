<?php declare(strict_types=1);
// command
namespace CryptoSim\Simulation\Application;

final class SaveTransaction
{
    private $portfolioId;
    private $cryptocurrencyId;
    private $USDAmount; // TODO - Make sure you validate this (don't trust values from the user without checking)
    private $cryptocurrencyAmount; // TODO - Make sure you validate this (don't trust values from the user without checking)
    private $type;

    public function __construct(
        string $portfolioId,
        int $cryptocurrencyId,
        string $USDAmount,
        string $type
    ){
        $this->portfolioId = $portfolioId;
        $this->cryptocurrencyId = $cryptocurrencyId;
        $this->USDAmount = $USDAmount;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getPortfolioId(): string
    {
        return $this->portfolioId;
    }

    /**
     * @return int
     */
    public function getCryptocurrencyId(): int
    {
        return $this->cryptocurrencyId;
    }

    /**
     * @return string
     */
    public function getUSDAmount(): string
    {
        return $this->USDAmount;
    }


    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}