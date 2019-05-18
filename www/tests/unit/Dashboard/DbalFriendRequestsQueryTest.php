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

    // TODO - Refactor this test and clean it up. Rename it also.
    // TODO - Move these integration tests into their own suite so database doesn't need to be repopulated after every
    //     unit test (especially ones that don't use the database)
    public function test()
    {
        // Setup database connection
        // TODO - Create test database and use those credentials
        $dbURL = 'mysql://root:tiger@mysql/crypto_sim_test';
        $databaseUrl = new DatabaseUrl($dbURL);
        $connectionFactory = new ConnectionFactory($databaseUrl);
        $connection = $connectionFactory->create();

        // Setup testUser1 session
        $testUser1SessionStorage = new MockArraySessionStorage();
        $testUser1Session = new Session($testUser1SessionStorage);
        $testUser1Id = '34369030-9a04-4371-b621-24fe3a267fe7';
        $testUser1Session->set('userId', $testUser1Id);

        // Setup testUser2 session
        $testUser2SessionStorage = new MockArraySessionStorage();
        $testUser2Session = new Session($testUser2SessionStorage);
        $testUser2Id = '5994685a-6783-4689-8308-ef2ec130bf2a';
        $testUser2Session->set('userId', $testUser2Id);

        // Add users to database
        $this->addUsersToDb();

        // Send friend request from testUser2 to testUser1
        $friendRequestsRepository = new DbalFriendRequestsRepository($connection, $testUser2Session);
        $friendRequestsRepository->send($testUser1Id);

        // Check that testUser2 received friend request
        $dbalFriendRequestsQuery = new DbalFriendRequestsQuery($connection, $testUser1Session);
        $friendRequests = $dbalFriendRequestsQuery->execute();

        $expectedFriendRequests = [
            new FriendRequest(
                'testUser2',
                $testUser2Id
            )
        ];

        $this->tester->assertEquals($expectedFriendRequests, $friendRequests);
    }

    private function addUsersToDb() {
//        $id = Uuid::uuid4();
        $this->tester->haveInDatabase('users',
            array(
                'id' => '34369030-9a04-4371-b621-24fe3a267fe7', // random UUID
                'nickname' => 'testUser1',
                'country' => 'USA',
                'password_hash' => '$2y$10$651uxkJ2CDNgQk0C9uM9hegJC.XHaksEf9i6Sp.PR7s7bBuyDtjHa', // 'password'
                'creation_date' => '2018-07-21 18:47:32' // random date
            )
        );

        $this->tester->haveInDatabase('users',
            array(
                'id' => '5994685a-6783-4689-8308-ef2ec130bf2a', // random UUID
                'nickname' => 'testUser2',
                'country' => 'USA',
                'password_hash' => '$2y$10$651uxkJ2CDNgQk0C9uM9hegJC.XHaksEf9i6Sp.PR7s7bBuyDtjHa', // 'password'
                'creation_date' => '2018-07-21 18:47:32' // random date
            )
        );
    }
}