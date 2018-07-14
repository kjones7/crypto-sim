<?php declare(strict_types=1);

namespace CryptoSim\User\Application;

final class FriendRequest
{
    private $nickname;
    private $fromUserId;

    public function __construct(
        string $nickname,
        string $fromUserId
    ){
        $this->nickname = $nickname;
        $this->fromUserId = $fromUserId;
    }

    public function getNickname() : string
    {
        return $this->nickname;
    }

    public function getFromUserId(): string
    {
        return $this->fromUserId;
    }
}