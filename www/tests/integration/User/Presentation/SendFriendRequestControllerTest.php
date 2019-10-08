<?php

use Codeception\Specify;
use Codeception\Test\Unit;
use CryptoSim\User\Application\FriendRequest;
use CryptoSim\User\Application\SendFriendRequestHandler;
use CryptoSim\User\Infrastructure\DbalFriendRequestsQuery;
use CryptoSim\User\Infrastructure\DbalFriendRequestsRepository;
use CryptoSim\User\Infrastructure\DbalGetFriendsListQuery;
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

        // TODO - Move this to a helper function
        $_POST = $mockPOSTData;
        $request = Request::createFromGlobals();

        // Send friend request from user2 to user1 using SendFriendRequestController
        $sendFriendRequestController->send($request);

        // Flash messages should contain error messages
        $this->tester->assertTrue($this->user2Session->getFlashBag()->has('errors'));
    }

    public function testShould_AcceptFriendRequest_When_PendingRequestAlreadyExistsFromUserThatRequestIsBeingSentTo()
    {
        // Setup $user1 data
        $user1FriendRequestRepository = new DbalFriendRequestsRepository($this->connection, $this->user1Session);
        $user1SendFriendRequestHandler = new SendFriendRequestHandler($user1FriendRequestRepository);
        $user1SendFriendRequestController = new SendFriendRequestController($user1SendFriendRequestHandler, $this->user1Session);
        $mockPOSTData = [
            'send-friend-request' => $this->user2->getNickname(),
            'userId' => $this->user2->getId()->toString(),
        ];
        $_POST = $mockPOSTData;
        $user1Request = Request::createFromGlobals();

        // Setup $user2 data
        $user2FriendRequestRepository = new DbalFriendRequestsRepository($this->connection, $this->user2Session);
        $user2SendFriendRequestHandler = new SendFriendRequestHandler($user2FriendRequestRepository);
        $user2SendFriendRequestController = new SendFriendRequestController($user2SendFriendRequestHandler, $this->user2Session);
        $mockPOSTData = [
            'send-friend-request' => $this->user1->getNickname(),
            'userId' => $this->user1->getId()->toString(),
        ];
        $_POST = $mockPOSTData;
        $user2Request = Request::createFromGlobals();

        // STEP 1. Send friend request from user1 to user2
        $user1SendFriendRequestController->send($user1Request);

        // STEP 2. Send a friend request from user2 to user1
        $user2SendFriendRequestController->send($user2Request);

        // STEP 3. Ensure that when user2 sends friend request, it just accepts the existing friend request from user1
        $user2GetFriendsListQuery = new DbalGetFriendsListQuery($this->connection, $this->user2Session);
        $user2FriendsList = $user2GetFriendsListQuery->execute($this->user2->getId());

        $this->tester->assertEquals(count($user2FriendsList), 1);

        $friend = $user2FriendsList[0];
        $this->tester->assertEquals($friend->getUserId(), $this->user1->getId());
    }
}