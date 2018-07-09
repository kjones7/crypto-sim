<?php declare(strict_types=1);

namespace CryptoSim\User\Application;

interface DoesNicknameExistQuery
{
    public function execute(string $nickname): bool;
}