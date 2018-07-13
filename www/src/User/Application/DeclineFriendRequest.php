<?php declare(strict_types = 1);

namespace CryptoSim\User\Application;

final class DeclineFriendRequest
{
    private $fromNickname;

    public function __construct($fromNickname)
    {
        $this->fromNickname = $fromNickname;
    }

    public function getFromNickname() : string
    {
        return $this->fromNickname;
    }
}