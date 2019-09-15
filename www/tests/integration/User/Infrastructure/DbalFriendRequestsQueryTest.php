<?php

use Codeception\Specify;
use Codeception\Test\Unit;
use CryptoSim\Framework\Csrf\Token;
use CryptoSim\Framework\Dbal\ConnectionFactory;
use CryptoSim\Framework\Dbal\DatabaseUrl;
use CryptoSim\User\Application\FriendRequest;
use CryptoSim\User\Infrastructure\DbalFriendRequestsQuery;
use CryptoSim\User\Infrastructure\DbalFriendRequestsRepository;
use josegonzalez\Dotenv\Loader;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Tracy\Debugger;

class DbalFriendRequestsQueryTest extends Unit
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

    public function testSendingFriendRequest()
    {
        // Setup database connection
        $connection = $this->tester->createDatabaseConnection();

        // Create users and sessions for those users
        $user1 = $this->tester->createRandomUser();
        $user2 = $this->tester->createRandomUser();
        $session1 = $this->tester->setupSessionForUser($user1);
        $session2 = $this->tester->setupSessionForUser($user2);

        // Add users to database
        $this->tester->addUserToDatabase($user1, $session1);
        $this->tester->addUsertoDatabase($user2, $session2);

        // Send friend request from user2 to user1
        $friendRequestsRepository = new DbalFriendRequestsRepository($connection, $session2);
        $friendRequestsRepository->send($user1->getId());

        // Check that user1 received friend request
        $dbalFriendRequestsQuery = new DbalFriendRequestsQuery($connection, $session1);
        $friendRequests = $dbalFriendRequestsQuery->execute();

        $expectedFriendRequests = [
            new FriendRequest(
                $user2->getNickname(),
                $user2->getId()
            )
        ];

        $this->tester->assertEquals($expectedFriendRequests, $friendRequests);
    }
}