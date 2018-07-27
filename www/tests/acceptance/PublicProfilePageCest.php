<?php
class PublicProfilePageCest
{
    private $testNickname = 'testUser';

    public function _before(AcceptanceTester $I)
    {
        $I->haveInDatabase('users',
            array(
                'id' => '52df8598-7664-4338-919f-be593af0941f', // random UUID
                'nickname' => $this->testNickname,
                'password_hash' => '$2y$10$651uxkJ2CDNgQk0C9uM9hegJC.XHaksEf9i6Sp.PR7s7bBuyDtjHa',
                'creation_date' => '2018-07-21 18:47:32'
            )
        );
        $I->amOnUrl('http://localhost/user/testUser');
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function seeContent(AcceptanceTester $I)
    {
        $I->see('CryptoSim');
        $I->see("Profile: {$this->testNickname}");
    }
}
