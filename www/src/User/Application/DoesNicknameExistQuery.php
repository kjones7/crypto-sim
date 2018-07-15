<?php declare(strict_types=1);

namespace CryptoSim\User\Application;

interface DoesNicknameExistQuery
{
    //TODO - Perhaps turn this into a function that returns the user data if it exists or NULL if it doesn't
    public function execute(string $nickname): bool;
}