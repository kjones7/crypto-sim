<?php declare(strict_types=1);
// command

namespace CryptoSim\User\Application;

final class SendFriendRequest
{
    private $ToNickname;
    private $toUserId;

    public function __construct(
        string $ToNickname,
        string $toUserId
    ){
        $this->ToNickname = $ToNickname;
        $this->toUserId = $toUserId;
    }

    public function getToUserId(): string
    {
        return $this->toUserId;
    }

    public function getToNickname(): string
    {
        return $this->ToNickname;
    }
}