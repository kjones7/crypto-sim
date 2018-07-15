<?php declare(strict_types=1);

namespace CryptoSim\User\Application;

interface GetPublicUserFromNicknameQuery
{
    public function execute(string $nickname): PublicUser;
}