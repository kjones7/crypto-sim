<?php declare(strict_types=1);

namespace CryptoSim\Framework\Rbac\Role;

use CryptoSim\Framework\Rbac\Permission;
use CryptoSim\Framework\Rbac\Role;
use CryptoSim\Framework\Rbac\Permission\CreatePortfolio;
use CryptoSim\Framework\Rbac\Permission\CanSeeDashboard;

final class PortfolioCreator extends Role
{
    /**
     * @return Permission[]
     */
    protected function getPermissions(): array
    {
        return [
            new CreatePortfolio(),
            new CanSeeDashboard()
        ];
    }
}