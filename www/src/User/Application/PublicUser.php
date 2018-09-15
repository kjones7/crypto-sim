<?php declare(strict_types=1);

namespace CryptoSim\User\Application;

final class PublicUser
{
    private $nickname;
    private $userId;
    private $country;

    public function __construct(
        string $nickname,
        string $userId,
        string $country
    ){
        $this->nickname = $nickname;
        $this->userId = $userId;
        $this->country = $country;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }
}