<?php declare(strict_types=1);

namespace CryptoSim\Simulation\Application;

final class OwnedCryptocurrency
{
    private $id;
    private $name;
    private $abbreviation;
    private $worthInUSD;
    private $quantity;
    private $percentChange;

    public function __construct(
        string $id,
        string $name,
        string $abbreviation,
        string $worthInUSD,
        string $quantity,
        string $percentChange
    ){
        $this->id = $id;
        $this->name = $name;
        $this->abbreviation = $abbreviation;
        $this->worthInUSD = $worthInUSD;
        $this->quantity = $quantity;
        $this->percentChange = $percentChange;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getAbbreviation(): string
    {
        return $this->abbreviation;
    }

    /**
     * @return string
     */
    public function getWorthInUSD(): string
    {
        return $this->worthInUSD;
    }

    /**
     * @return string
     */
    public function getQuantity(): string
    {
        return $this->quantity;
    }

    /**
     * @return string
     */
    public function getPercentChange(): string
    {
        return $this->percentChange;
    }

    public function jsonify() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'abbreviation' => $this->abbreviation,
            'worthInUSD' => $this->worthInUSD,
            'quantity' => $this->quantity,
            'percentChange' => $this->percentChange
        ];
    }
}