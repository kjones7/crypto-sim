<?php declare(strict_types=1);
// entity
namespace CryptoSim\Simulation\Domain;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use DateTimeImmutable;

final class Transaction
{
    private $id;
    private $portfolioId;
    private $cryptocurrencyId;
    private $USDAmount;
    private $type;
    private $date;

    private function __construct(
        UuidInterface $id,
        string $portfolioId,
        int $cryptocurrencyId,
        string $USDAmount,
        string $type,
        DateTimeImmutable $date
    ){
        $this->id = $id;
        $this->portfolioId = $portfolioId;
        $this->cryptocurrencyId = $cryptocurrencyId;
        $this->USDAmount = $USDAmount;
        $this->type = $type;
        $this->date = $date;
    }

    public static function save(
        string $portfolioId,
        int $cryptocurrencyId,
        string $USDAmount,
        string $type
    ): Transaction {
        return new Transaction(
            Uuid::uuid4(),
            $portfolioId,
            $cryptocurrencyId,
            $USDAmount,
            $type,
            new DateTimeImmutable()
        );
    }
    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
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
    public function getCryptocurrencyAmount(): string
    {
        return $this->cryptocurrencyAmount;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }
}