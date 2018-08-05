<?php declare(strict_types=1);

namespace CryptoSim\Simulation\Application;

final class Portfolio
{
    private $id;
    private $title;

    public function __construct(
        string $id,
        string $title
    ){
        $this->id = $id;
        $this->title = $title;
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
}