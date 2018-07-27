<?php

class LogInCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->amOnUrl('http://localhost/login'); // url in acceptance.suite.yml not working, so set url here
    }

    public function _after(AcceptanceTester $I)
    {
    }

    public function login(AcceptanceTester $I)
    {
        $I->haveInDatabase('users',
            array(
                'id' => '52df8598-7664-4338-919f-be593af0941f', // random UUID
                'nickname' => 'testUser',
                'password_hash' => '$2y$10$651uxkJ2CDNgQk0C9uM9hegJC.XHaksEf9i6Sp.PR7s7bBuyDtjHa',
                'creation_date' => '2018-07-21 18:47:32'
            )
        );
        $I->fillField('nickname', 'testUser');
        $I->fillField('password', 'password');
        $I->click('Log in');
        $I->waitForElement('.welcome-user', 10);
        $I->seeInDatabase('users',
            array(
                'nickname' => 'testUser',
            )
        );
    }
}
