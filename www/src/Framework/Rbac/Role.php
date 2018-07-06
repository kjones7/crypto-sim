<?php declare(strict_types=1);

namespace CryptoSim\Framework\Rbac;

abstract class Role
{
    public function hasPermission(Permission $permission): bool
    {
        return in_array($permission, $this->getPermissions());
    }
    
    /**
    * @return Permission[]
    */
    abstract protected function getPermissions(): array;
}