<?php declare(strict_types=1);
// value object
// TODO - Change this file to the dashboard module eventually
namespace CryptoSim\Portfolio\Application;

final class Portfolio
{
    private $id;
    private $type;
    private $title;
    private $visibility;
    private $status;
    private $worthInUSD;

    public function __construct(
        string $id,
        string $type,
        string $title,
        string $visibility,
        string $status,
        string $worthInUSD
    ){
        $this->id = $id;
        $this->type = $type;
        $this->title = $title;
        $this->visibility = $visibility;
        $this->status = $status;
        $this->worthInUSD = $worthInUSD;
    }

    /**
     * @return string
     */
    public function getVisibility(): string
    {
        return $this->visibility;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
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
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getWorthInUSD(): string
    {
        return $this->worthInUSD;
    }
}
