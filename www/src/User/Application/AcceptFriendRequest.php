<?php declare(strict_types = 1);

namespace CryptoSim\User\Application;

final class AcceptFriendRequest
{
    private $fromUserId;

    public function __construct($fromUserId)
    {
        $this->fromUserId = $fromUserId;
    }

    public function getFromUserId() : string
    {
        return $this->fromUserId;
    }
}