<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use AcceptanceTester;
use Codeception\Actor;
use Ramsey\Uuid\Uuid;


class Acceptance extends \Codeception\Module
{
    private $testNickname1 = 'testUser1';
    private $testNickname2 = 'testUser2';
    private $testNickname3 = 'testUser3';
    private $testNickname4 = 'testUser4';
    private $testNickname5 = 'testUser5';

    private $testPassword = 'password';

    public function _beforeSuite($settings = array())
    {
        // change environment to 'test' (allows use of test database by application)
        $path = 'app.env';
        if (file_exists($path)) {
            file_put_contents($path, str_replace(
                'APP_ENV=dev', 'APP_ENV=test', file_get_contents($path)
            ));
        }
    }

    public function _afterSuite($settings = array())
    {
        // Change environment back to 'dev'
        $path = 'app.env';
        if (file_exists($path)) {
            file_put_contents($path, str_replace(
                'APP_ENV=test', 'APP_ENV=dev', file_get_contents($path)
            ));
        }
    }

    public function loginWithTestUser1(Actor $I) {
        $this->logIn($I, $this->testNickname1, $this->testPassword);
    }

    public function logIn(
        Actor $I,
        string $nickname,
        string $password
    ){
        $I->amOnUrl('http://localhost/login');
        $I->fillField('nickname', $nickname);
        $I->fillField('password', $password);
        $I->click('.login-button');
    }

    public function populateDbWithTestUsers(
        Actor $I
    ) {
        $testNicknames = [
            $this->testNickname1,
            $this->testNickname2,
            $this->testNickname3,
            $this->testNickname4,
            $this->testNickname5,
        ];

        foreach($testNicknames as $testNickname) {
            $this->addUserToDb($I, $testNickname);
        }
    }

    public function addUserToDb(
        Actor $I,
        string $nickname
    ){
        $id = Uuid::uuid4();
        $I->haveInDatabase('users',
            array(
                'id' => $id, // random UUID
                'nickname' => $nickname,
                'country' => 'USA',
                'password_hash' => '$2y$10$651uxkJ2CDNgQk0C9uM9hegJC.XHaksEf9i6Sp.PR7s7bBuyDtjHa', // 'password'
                'creation_date' => '2018-07-21 18:47:32' // random date
            )
        );

        return $id;
    }

    public function sendFriendRequest(
        AcceptanceTester $I,
        string $toNickname
    ){
        $I->amOnUrl("http://localhost/user/{$toNickname}");
        $I->click('.send-friend-request-btn');
    }

    /**
     * @param AcceptanceTester $I
     * @param string[][] $friendRequests
     */
    public function addFriendsToDb(
        AcceptanceTester $I,
        array $friendRequests
    ){
        foreach($friendRequests as $friendRequest) {
            $I->haveInDatabase('friends',
                array(
                    'id' => Uuid::uuid4(), // random UUID
                    'to_user_id' => $friendRequest['toUserId'],
                    'from_user_id' => $friendRequest['fromUserId'],
                    'date_sent' => '2018-07-24 01:41:08'
                )
            );
        }
    }
}
