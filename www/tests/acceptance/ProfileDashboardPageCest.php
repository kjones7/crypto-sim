<?php
class ProfileDashboardPageCest
{
    private $testNickname = 'testUser';
    private $testPassword = 'password';

    public function _before(AcceptanceTester $I)
    {
        $I->addUserToDb($I, $this->testNickname);
        $I->logIn($I, $this->testNickname, $this->testPassword);
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function seeContent(AcceptanceTester $I)
    {
        $I->see('CryptoSim');
        $I->see("Welcome, {$this->testNickname}");
        $I->see('Friend Requests');
        $I->dontSeeElement('.friend-request');
        $I->dontSeeElement('.friend');
    }
}
