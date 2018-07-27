<?php

class FrontPageCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->amOnUrl('http://localhost'); // url in acceptance.suite.yml not working, so set url here
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function seeContent(AcceptanceTester $I)
    {
        $I->see('CryptoSim');
        $I->see('Welcome to CryptoSim!');
    }
}
