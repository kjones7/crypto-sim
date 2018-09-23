<?php declare(strict_types=1);

namespace CryptoSim\Simulation\Application;

final class Cryptocurrency implements \JsonSerializable
{
    private $id;
    private $name;
    private $abbreviation;
    private $worthInUSD;

    public function __construct(
        string $id,
        string $name,
        string $abbreviation,
        string $worthInUSD
    ){
        $this->id = $id;
        $this->name = $name;
        $this->abbreviation = $abbreviation;
        $this->worthInUSD = $worthInUSD;
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

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'abbreviation' => $this->getAbbreviation(),
            'name' => $this->getName(),
            'worthInUSD' => $this->getWorthInUSD()
        ];
    }
}