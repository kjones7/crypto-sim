<?php declare(strict_types=1);

namespace CryptoSim\User\Domain;

use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

final class User
{
    private $id;
    private $nickname;
    private $passwordHash;
    private $creationDate;

    private function __construct(
        UuidInterface $id,
        string $nickname,
        string $passwordHash,
        DateTimeImmutable $creationDate
    ) {
        $this->id = $id;
        $this->nickname = $nickname;
        $this->passwordHash = $passwordHash;
        $this->creationDate = $creationDate;
    }

    public static function register(string $nickname, string $password): User
    {
        return new User(
            Uuid::uuid4(),
            $nickname,
            password_hash($password, PASSWORD_DEFAULT),
            new DateTimeImmutable()
        );
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }
    
    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getCreationDate(): DateTimeImmutable
    {
        return $this->creationDate;
    }
}