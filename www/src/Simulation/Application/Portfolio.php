<?php declare(strict_types=1);

namespace CryptoSim\Simulation\Application;

final class Portfolio
{
    private $id;
    private $title;
    private $USDAmount;
    private $cryptoWorthInUSD;
    private $portfolioWorth;

    public function __construct(
        string $id,
        string $title,
        string $USDAmount,
        string $cryptoWorthInUSD,
        string $portfolioWorth
    ){
        $this->id = $id;
        $this->title = $title;
        $this->USDAmount = $USDAmount;
        $this->cryptoWorthInUSD = $cryptoWorthInUSD;
        $this->portfolioWorth = $portfolioWorth;
    }
    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
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
    public function getCryptoWorthInUSD(): string
    {
        return $this->cryptoWorthInUSD;
    }

    /**
     * @return string
     */
    public function getPortfolioWorth(): string
    {
        return $this->portfolioWorth;
    }
}