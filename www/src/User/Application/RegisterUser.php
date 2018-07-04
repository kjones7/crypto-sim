<?php declare(strict_types=1);
// this class is a command, the handler is RegiserUserHandler.php

namespace CryptoSim\User\Application;

final class RegisterUser
{
    private $nickname;
    private $password;

    public function __construct(string $nickname, string $password)
    {
        $this->nickname = $nickname;
        $this->password = $password;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}