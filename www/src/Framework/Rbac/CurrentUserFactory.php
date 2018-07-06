<?php declare(strict_types=1);

namespace CryptoSim\Framework\Rbac;

interface CurrentUserFactory
{
    public function create(): User;
}