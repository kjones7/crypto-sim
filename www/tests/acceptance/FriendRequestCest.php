<?php
class FriendRequestCest
{
    private $testNickname1 = 'testUser1';
    private $testNickname2 = 'testUser2';
    private $testNickname3 = 'testUser3';
    private $testNickname4 = 'testUser4';
    private $testNickname5 = 'testUser5';
    private $testPassword = 'password';
    private $testUser1ID;
    private $testUser2ID;
    private $testUser3ID;
    private $testUser4ID;
    private $testUser5ID;

    // TODO - Use saveSessionSnapshot() instead of repeatedly logging in
    public function _before(AcceptanceTester $I)
    {
        $this->testUser1ID = $I->addUserToDb($I, $this->testNickname1);
        $this->testUser2ID = $I->addUserToDb($I, $this->testNickname2);
        $this->testUser3ID = $I->addUserToDb($I, $this->testNickname3);
        $this->testUser4ID = $I->addUserToDb($I, $this->testNickname4);
        $this->testUser5ID = $I->addUserToDb($I, $this->testNickname5);
        $I->logIn($I, $this->testNickname1, $this->testPassword); // TODO - Take this out of the _before function
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function seeContent(AcceptanceTester $I)
    {
        $I->see('CryptoSim');
        $I->see("Welcome, {$this->testNickname1}");
        $I->see('Friend Requests');
        $I->dontSeeElement('.friend-request');
        $I->dontSeeElement('.friend');
    }

    public function sendFriendRequest(AcceptanceTester $I)
    {
        // As testUser1
        $I->sendFriendRequest($I, $this->testNickname2);
        $I->sendFriendRequest($I, $this->testNickname3);
        $I->sendFriendRequest($I, $this->testNickname4);
        $I->sendFriendRequest($I, $this->testNickname5);

        // As testUser2
        $I->logIn($I, $this->testNickname2, $this->testPassword);
        $I->sendFriendRequest($I, $this->testNickname5);

        // As testUser3
        $I->logIn($I, $this->testNickname3, $this->testPassword);
        $I->sendFriendRequest($I, $this->testNickname5);

        // As testUser4
        $I->logIn($I, $this->testNickname4, $this->testPassword);
        $I->sendFriendRequest($I, $this->testNickname3);
        $I->sendFriendRequest($I, $this->testNickname5);

        // testUser5 send no friend requests

        // Now check friend requests of each user

        // As testUser4 (1 friend request from testUser1)
        $I->amOnPage('/dashboard');
        $I->makeScreenshot('testUser4_friend_requests');
        $I->seeNumberOfElements('.friend-request', 1);
        $I->seeNumberOfElements('.accept-friend-request-btn', 1);
        $I->seeNumberOfElements('.decline-friend-request-btn', 1);
        $I->seeLink($this->testNickname1, "/user/{$this->testNickname1}");

        // As testUser5 (4 friend request from testUser1, testUser2, testUser3, testUser4)
        $I->logIn($I, $this->testNickname5, $this->testPassword);
        $I->seeCurrentUrlEquals('/dashboard');
        $I->makeScreenshot('testUser5_friend_requests');
        $I->seeNumberOfElements('.friend-request', 4);
        $I->seeNumberOfElements('.accept-friend-request-btn', 4);
        $I->seeNumberOfElements('.decline-friend-request-btn', 4);
        $I->seeLink($this->testNickname1, "/user/{$this->testNickname1}");
        $I->seeLink($this->testNickname2, "/user/{$this->testNickname2}");
        $I->seeLink($this->testNickname3, "/user/{$this->testNickname3}");
        $I->seeLink($this->testNickname4, "/user/{$this->testNickname4}");

        // As testUser1 (no friend requests)
        $I->logIn($I, $this->testNickname1, $this->testPassword);
        $I->seeCurrentUrlEquals('/dashboard');
        $I->makeScreenshot('testUser1_friend_requests');
        $I->seeNumberOfElements('.friend-request', 0);
        $I->seeNumberOfElements('.accept-friend-request-btn', 0);
        $I->seeNumberOfElements('.decline-friend-request-btn', 0);

        // As testUser2 (1 friend request from testUser1)
        $I->logIn($I, $this->testNickname2, $this->testPassword);
        $I->seeCurrentUrlEquals('/dashboard');
        $I->makeScreenshot('testUser2_friend_requests');
        $I->seeNumberOfElements('.friend-request', 1);
        $I->seeNumberOfElements('.accept-friend-request-btn', 1);
        $I->seeNumberOfElements('.decline-friend-request-btn', 1);
        $I->seeLink($this->testNickname1, "/user/{$this->testNickname1}");

        // As testUser3 (2 friend requests from testUser1 and testUser4)
        $I->logIn($I, $this->testNickname3, $this->testPassword);
        $I->seeCurrentUrlEquals('/dashboard');
        $I->makeScreenshot('testUser3_friend_requests');
        $I->seeNumberOfElements('.friend-request', 2);
        $I->seeNumberOfElements('.accept-friend-request-btn', 2);
        $I->seeNumberOfElements('.decline-friend-request-btn', 2);
        $I->seeLink($this->testNickname1, "/user/{$this->testNickname1}");
        $I->seeLink($this->testNickname4, "/user/{$this->testNickname4}");
    }

    public function acceptOrDeclineFriendRequests(AcceptanceTester $I)
    {
        // toUserId => fromUserId
        $friends = array(
            ['toUserId' => $this->testUser2ID , 'fromUserId'=> $this->testUser1ID], // will be accepted
            ['toUserId' => $this->testUser3ID , 'fromUserId'=> $this->testUser1ID], // will be declined
            ['toUserId' => $this->testUser4ID , 'fromUserId'=> $this->testUser1ID], // will be accepted
            ['toUserId' => $this->testUser5ID , 'fromUserId'=> $this->testUser1ID], // will be declined
            ['toUserId' => $this->testUser5ID , 'fromUserId'=> $this->testUser2ID], // will be accepted
            ['toUserId' => $this->testUser5ID , 'fromUserId'=> $this->testUser3ID], // will be accepted
            ['toUserId' => $this->testUser3ID , 'fromUserId'=> $this->testUser4ID], // will be declined
            ['toUserId' => $this->testUser5ID , 'fromUserId'=> $this->testUser4ID] // will be accepted
        );
        $I->addFriendsToDb($I, $friends);

        // testUser1 has no friend requests, skip

        // As testUser2
        $I->logIn($I, $this->testNickname2, $this->testPassword);
        $I->seeCurrentUrlEquals('/dashboard');
        $I->seeElement("#{$this->testNickname1}-friend-request");
        $I->click("#{$this->testNickname1}-friend-request .accept-friend-request-btn");
        // check friends list for accepted friends
        $I->seeLink($this->testNickname1, "/user/{$this->testNickname1}");

        // As testUser3
        $I->logIn($I, $this->testNickname3, $this->testPassword);
        $I->seeCurrentUrlEquals('/dashboard');
        $I->seeElement("#{$this->testNickname1}-friend-request");
        $I->seeElement("#{$this->testNickname4}-friend-request");
        $I->click("#{$this->testNickname1}-friend-request .decline-friend-request-btn");
        $I->click("#{$this->testNickname4}-friend-request .decline-friend-request-btn");
        // check friends list to make sure it's empty
        $I->dontSeeElement('.friend');

        // As testUser4
        $I->logIn($I, $this->testNickname4, $this->testPassword);
        $I->seeCurrentUrlEquals('/dashboard');
        $I->seeElement("#{$this->testNickname1}-friend-request");
        $I->click("#{$this->testNickname1}-friend-request .accept-friend-request-btn");
        // check friends list for accepted friends
        $I->seeLink($this->testNickname1, "/user/{$this->testNickname1}");

        // As testUser5
        $I->logIn($I, $this->testNickname5, $this->testPassword);
        $I->seeCurrentUrlEquals('/dashboard');
        $I->seeElement("#{$this->testNickname1}-friend-request");
        $I->seeElement("#{$this->testNickname2}-friend-request");
        $I->seeElement("#{$this->testNickname3}-friend-request");
        $I->seeElement("#{$this->testNickname4}-friend-request");
        $I->click("#{$this->testNickname1}-friend-request .decline-friend-request-btn");
        $I->click("#{$this->testNickname2}-friend-request .accept-friend-request-btn");
        $I->click("#{$this->testNickname3}-friend-request .accept-friend-request-btn");
        $I->click("#{$this->testNickname4}-friend-request .accept-friend-request-btn");
        // check friends list for accepted friends
        $I->seeLink($this->testNickname2, "/user/{$this->testNickname2}");
        $I->seeLink($this->testNickname3, "/user/{$this->testNickname3}");
        $I->seeLink($this->testNickname4, "/user/{$this->testNickname4}");
    }
}
