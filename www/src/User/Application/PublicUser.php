<?php declare(strict_types=1);

namespace CryptoSim\User\Application;

final class PublicUser
{
    private $nickname;
    private $userId;

    public function __construct(
        string $nickname,
        string $userId
    ){
        $this->nickname = $nickname;
        $this->userId = $userId;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
}