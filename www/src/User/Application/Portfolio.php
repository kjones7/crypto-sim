<?php declare(strict_types=1);

namespace CryptoSim\User\Application;

final class Portfolio
{
    private $id;
    private $type;
    private $title;
    private $worthInUSD;

    public function __construct(
        string $id,
        string $type,
        string $title,
        string $worthInUSD
    ){
        $this->id = $id;
        $this->type = $type;
        $this->title = $title;
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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getWorthInUSD()
    {
        return $this->worthInUSD;
    }
}