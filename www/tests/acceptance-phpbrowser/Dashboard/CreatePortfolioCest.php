<?php
class CreatePortfolioCest
{

    // TODO - Use saveSessionSnapshot() instead of repeatedly logging in
    public function _before(PhpBrowserTester $I)
    {
        $I->populateDbWithTestUsers($I);
    }

    public function _after(PhpBrowserTester $I)
    {
    }

    // TODO - Finish this test
    public function testing(PhpBrowserTester $I) {
        $I->amOnPage('/login');
        $I->logInWithTestUser1($I);
    }
}
