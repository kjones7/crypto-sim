<?php

use Codeception\Specify;
use Codeception\Test\Unit;
use CryptoSim\Framework\Csrf\Token;
use CryptoSim\Framework\Dbal\ConnectionFactory;
use CryptoSim\Framework\Dbal\DatabaseUrl;
use CryptoSim\User\Application\FriendRequest;
use CryptoSim\User\Infrastructure\DbalFriendRequestsQuery;
use CryptoSim\User\Infrastructure\DbalFriendRequestsRepository;
use CryptoSim\User\Infrastructure\DbalGetFriendsListQuery;
use josegonzalez\Dotenv\Loader;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Tracy\Debugger;

class DbalFriendRequestsRepositoryTest extends Unit
{
    use Specify;
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testShould_ThrowError_When_acceptingInvalidFriendRequest()
    {
        // Setup database connection
        $connection = $this->tester->createDatabaseConnection();

        // Create users and sessions for those users
        $user1 = $this->tester->createRandomUser();
        $user2 = $this->tester->createRandomUser();
        $user3 = $this->tester->createRandomUser();
        $session1 = $this->tester->setupSessionForUser($user1);
        $session2 = $this->tester->setupSessionForUser($user2);
        $session3 = $this->tester->setupSessionForUser($user2);

        // Add users to database
        $this->tester->addUserToDatabase($user1, $session1);
        $this->tester->addUsertoDatabase($user2, $session2);
        $this->tester->addUsertoDatabase($user3, $session2);

        // user1 sends friend request to user2
        $friendRequestsRepository = new DbalFriendRequestsRepository($connection, $session1);
        $friendRequestsRepository->send($user2->getId());

        // user2 declines
        $user2FriendRequestsRepository = new DbalFriendRequestsRepository($connection, $session2);
        $user2FriendRequestsRepository->decline($user1->getId());

        // user1 wants to be user2's friend, but user2 doesn't want to be friends :(
        // user1 maliciously sends a fake request to accept a friend request, using user2's userId
        $friendRequestsRepository->accept($user2->getId());

        // Ensure user1 and user2 are still not friends
        $user1GetFriendsListQuery = new DbalGetFriendsListQuery($connection, $session1);
        $user1FriendsList = $user1GetFriendsListQuery->execute($user1->getId());

        $this->tester->assertEmpty($user1FriendsList);
    }
}