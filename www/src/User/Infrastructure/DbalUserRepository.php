<?php declare(strict_types=1);

namespace CryptoSim\User\Infrastructure;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Connection;
use CryptoSim\User\Domain\User;
use CryptoSim\User\Domain\UserRepository;

final class DbalUserRepository implements UserRepository
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function add(User $user): void
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->insert('users');
        $qb->values([
            'id' => $qb->createNamedParameter($user->getId()->toString()),
            'nickname' => $qb->createNamedParameter($user->getNickname()),
            'password_hash' => $qb->createNamedParameter(
                $user->getPasswordHash()
            ),
            'creation_date' => $qb->createNamedParameter(
                $user->getCreationDate(),
                Type::DATETIME
            ),
        ]);

        $qb->execute();
    }
}