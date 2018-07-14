<?php declare(strict_types = 1);

namespace CryptoSim\User\Application;

final class DeclineFriendRequest
{
    private $fromUserId;

    public function __construct(string $fromUserId)
    {
        $this->fromUserId = $fromUserId;
    }

    public function getFromUserId() : string
    {
        return $this->fromUserId;
    }
}