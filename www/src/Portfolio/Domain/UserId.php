<?php declare(strict_types=1);

namespace CryptoSim\Portfolio\Domain;

use Ramsey\Uuid\UuidInterface;

final class UserId
{
    private $id;

    private function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function fromUuid(UuidInterface $uuid): UserId
    {
        return new UserId($uuid->toString());
    }

    public function toString(): string
    {
        return $this->id;
    }
}