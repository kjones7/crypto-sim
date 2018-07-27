<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use AcceptanceTester;
use Ramsey\Uuid\Uuid;


class Acceptance extends \Codeception\Module
{
    public function logIn(
        AcceptanceTester $I,
        string $nickname,
        string $password
    ){
        $I->amOnUrl('http://localhost/login');
        $I->fillField('nickname', $nickname);
        $I->fillField('password', $password);
        $I->click('Log in');
    }

    public function addUserToDb(
        AcceptanceTester $I,
        string $nickname
    ){
        $id = Uuid::uuid4();
        $I->haveInDatabase('users',
            array(
                'id' => $id, // random UUID
                'nickname' => $nickname,
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
