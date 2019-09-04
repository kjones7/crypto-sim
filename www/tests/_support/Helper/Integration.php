<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use CryptoSim\Framework\Dbal\ConnectionFactory;
use CryptoSim\Framework\Dbal\DatabaseUrl;
use CryptoSim\User\Domain\User;
use CryptoSim\User\Infrastructure\DbalUserRepository;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Ramsey\Uuid\Uuid;

class Integration extends \Codeception\Module
{
    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function createDatabaseConnection() : Connection {
        $dbURL = 'mysql://root:rootpass@mysql/crypto_sim_test';
        $databaseUrl = new DatabaseUrl($dbURL);
        $connectionFactory = new ConnectionFactory($databaseUrl);
        return $connectionFactory->create();
    }

    /**
     * @return User
     * @throws \Exception
     */
    public function createRandomUser(): User {
        $nickname = Uuid::uuid4()->toString();
        $password = Uuid::uuid4()->toString();
        $country = 'USA';

        $user = User::register(
            $nickname,
            $password,
            $country
        );

        return $user;
    }

    public function addUserToDatabase(User $user, SessionInterface $session) {
        $userRepository = new DbalUserRepository(
            $this->createDatabaseConnection(),
            $session
        );

        $userRepository->add($user);
    }

    /**
     * @param User $user
     * @return SessionInterface
     * @throws \Exception
     */
    public function setupSessionForUser(User $user) : SessionInterface {
        $userId = $user->getId();

        $sessionStorage = new MockArraySessionStorage();
        $session = new Session($sessionStorage);
        $session->set('userId', $userId);

        return $session;
    }
}
