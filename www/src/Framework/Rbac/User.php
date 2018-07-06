<?php declare(strict_types=1);

namespace CryptoSim\Framework\Rbac;

interface User
{
    public function hasPermission(Permission $permission): bool;
}