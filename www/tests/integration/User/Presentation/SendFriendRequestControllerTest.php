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


        $friendRequestRepository = new DbalFriendRequestsRepository($connection, $session2);
        $sendFriendRequestHandler = new SendFriendRequestHandler($friendRequestRepository);
        $sendFriendRequestController = new SendFriendRequestController($sendFriendRequestHandler);

        $mockPOSTData = [
            'send-friend-request' => $user1->getNickname(),
            'userId' => $user1->getId()->toString(),
        ];

        // TODO - When creating request, $_COOKIE should be set with the ID of the session (PHPSESSID cookie)
//        $mockCOOKIEData = [
//            'PHPSESSID' => $session2->get('userId'),
//        ];

        $_POST = $mockPOSTData;
//        $_COOKIE = $mockCOOKIEData;

        $request = Request::createFromGlobals();

        // Send friend request from user2 to user1 using SendFriendRequestController
        $sendFriendRequestController->send($request);

        // Check that user1 received friend request from user2
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