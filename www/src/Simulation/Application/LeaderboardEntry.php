<?php declare(strict_types=1);

namespace CryptoSim\Simulation\Application;

final class LeaderboardEntry implements \JsonSerializable
{
    private $position;
    private $username;
    private $portfolioWorth;
    private $portfolioTitle;

    public function __construct(
        int $position,
        string $username,
        string $portfolioWorth,
        string $portfolioTitle
    ){
        $this->position = $position;
        $this->username = $username;
        $this->portfolioWorth = $portfolioWorth;
        $this->portfolioTitle = $portfolioTitle;
    }

    /**
     * @return string
     */
    public function getPortfolioWorth(): string
    {
        return $this->portfolioWorth;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPortfolioTitle(): string
    {
        return $this->portfolioTitle;
    }

    public function jsonSerialize()
    {
        return [
            'position' => $this->position,
            'username' => $this->username,
            'portfolioWorth' => $this->portfolioWorth,
            'portfolioName' => $this->portfolioTitle
        ];
    }
}