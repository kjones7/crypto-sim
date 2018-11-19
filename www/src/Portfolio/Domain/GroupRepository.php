<?php declare(strict_types=1);

namespace CryptoSim\Portfolio\Domain;

interface GroupRepository
{
    public function create(Portfolio $portfolio): string;
    public function getGroupInvitesForUser(string $userId): array;
}