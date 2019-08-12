<?php
namespace PhpBrowserAcceptance\Dashboard;

use PhpBrowserTester;

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

    // TODO - Look into codeception extensions/events to save session data before running suite
    public function testing(PhpBrowserTester $I) {
        $I->amOnPage('/login');
        $I->logInWithTestUser1($I);
    }
}
