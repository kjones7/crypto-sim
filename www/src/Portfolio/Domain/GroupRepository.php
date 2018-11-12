<?php declare(strict_types=1);

namespace CryptoSim\Portfolio\Domain;

interface GroupRepository
{
    public function create(): string;
}