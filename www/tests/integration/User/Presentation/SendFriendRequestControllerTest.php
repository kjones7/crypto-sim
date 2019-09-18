<?php

use Codeception\Specify;
use Codeception\Test\Unit;
use CryptoSim\User\Application\FriendRequest;
use CryptoSim\User\Application\SendFriendRequestHandler;
use CryptoSim\User\Infrastructure\DbalFriendRequestsQuery;
use CryptoSim\User\Infrastructure\DbalFriendRequestsRepository;
use CryptoSim\User\Presentation\SendFriendRequestController;
use Symfony\Component\HttpFoundation\Request;

class SendFriendRequestControllerTest extends Unit
{
    use Specify;
    /**
     * @var \UnitTester
     */
    protected $tester;

    private $connection;
    private $user1;
    private $user2;
    private $user1Session;
    private $user2Session;

    protected function _before()
    {
        // TODO - This doesn't need to be run before each test, the users could just be added once
        // Setup database connection
        $this->connection = $this->tester->createDatabaseConnection();

        // Create users and sessions for those users
        $this->user1 = $this->tester->createRandomUser();
        $this->user2 = $this->tester->createRandomUser();
        $this->user1Session = $this->tester->setupSessionForUser($this->user1);
        $this->user2Session = $this->tester->setupSessionForUser($this->user2);

        // Add users to database
        $this->tester->addUserToDatabase($this->user1, $this->user1Session);
        $this->tester->addUsertoDatabase($this->user2, $this->user2Session);
    }

    protected function _after()
    {
    }

    public function testSendingFriendRequest()
    {
        $friendRequestRepository = new DbalFriendRequestsRepository($this->connection, $this->user2Session);
        $sendFriendRequestHandler = new SendFriendRequestHandler($friendRequestRepository);
        $sendFriendRequestController = new SendFriendRequestController($sendFriendRequestHandler, $this->user2Session);

        $mockPOSTData = [
            'send-friend-request' => $this->user1->getNickname(),
            'userId' => $this->user1->getId()->toString(),
        ];

        // TODO - When creating request, $_COOKIE should be set with the ID of the session (PHPSESSID cookie)
//        $mockCOOKIEData = [
//            'PHPSESSID' => $this->user2Session->get('userId'),
//        ];

        $_POST = $mockPOSTData;
//        $_COOKIE = $mockCOOKIEData;

        $request = Request::createFromGlobals();

        // Send friend request from user2 to user1 using SendFriendRequestController
        $sendFriendRequestController->send($request);

        // Check that user1 received friend request from user2
        $dbalFriendRequestsQuery = new DbalFriendRequestsQuery($this->connection, $this->user1Session);
        $friendRequests = $dbalFriendRequestsQuery->execute();

        $expectedFriendRequests = [
            new FriendRequest(
                $this->user2->getNickname(),
                $this->user2->getId()
            )
        ];

        $this->tester->assertEquals($expectedFriendRequests, $friendRequests);

    }

    public function testShould_HaveErrors_When_toUserIDIsInvalid()
    {
        // $user2 sends a friend request to a user ID that doesn't exist
        $friendRequestRepository = new DbalFriendRequestsRepository($this->connection, $this->user2Session);
        $sendFriendRequestHandler = new SendFriendRequestHandler($friendRequestRepository);
        $sendFriendRequestController = new SendFriendRequestController($sendFriendRequestHandler, $this->user2Session);

        $mockPOSTData = [
            'send-friend-request' => $this->user1->getNickname(),
            'userId' => '12345', // user ID does not exist
        ];

        $_POST = $mockPOSTData;

        $request = Request::createFromGlobals();

        // Send friend request from user2 to user1 using SendFriendRequestController
        $sendFriendRequestController->send($request);

        // Flash messages should contain error messages
        $this->tester->assertTrue($this->user2Session->getFlashBag()->has('errors'));
    }
}