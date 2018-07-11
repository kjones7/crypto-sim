<?php declare(strict_types=1);

namespace CryptoSim\User\Application;

final class FriendRequest
{
    private $nickname;

    public function __construct(string $nickname)
    {
        $this->nickname = $nickname;
    }

    public function getNickname() : string
    {
        return $this->nickname;
    }
}