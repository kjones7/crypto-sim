<?php

class RegisterCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->amOnUrl('http://localhost/register'); // url in acceptance.suite.yml not working, so set url here
    }

    public function _after(AcceptanceTester $I)
    {
    }

    public function register(AcceptanceTester $I)
    {
        $I->fillField('nickname', 'testUser');
        $I->fillField('password', 'password');
        $I->click('Register');
        $I->waitForElement('.flash-success', 10);
        $I->seeInDatabase('users',
            array(
                'nickname' => 'testUser',
            )
        );
    }
}
