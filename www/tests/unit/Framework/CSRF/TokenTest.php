<?php
use CryptoSim\Framework\Csrf\Token;

class TokenTest extends \Codeception\Test\Unit
{
    use \Codeception\Specify;
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @specify
     * @var Token
     */
    private $token;
    /**
     * @specify
     * @var string
     */
    private $tokenID;

    public function testTokenCreationWithDummyID()
    {
        $this->tokenID = 'This is a token identifier';
        $this->token = new Token($this->tokenID);

        $this->specify("Token was created correctly", function() {
            $this->assertEquals($this->tokenID, $this->token->toString());
        });
    }

    /**
     * @throws Exception
     */
    public function testTokenCreationByGeneratingID()
    {
        $this->token = Token::generate();

        $this->specify("Token was created correctly by generating ID", function() {
            $this->assertInternalType("string", $this->token->toString());
        });

        $this->specify("Token has correct length", function() {
            $this->assertEquals(512, strlen($this->token->toString()));
        });

        $this->specify("Two tokens generate differently", function() {
            $anotherToken = Token::generate();

            $this->assertNotEquals($this->toString(), $anotherToken->toString());
        });
    }

    /**
     * @throws Exception
     */
    public function testTokenEqualityComparison()
    {
        $this->token = Token::generate();

        $this->specify("Two identical tokens are equal", function() {
            $tokenClone = $this->token;
            $this->assertTrue($this->token->equals($tokenClone));
        });

        $this->specify("Two different tokens are not equal", function() {
            $newToken = Token::generate();
            $this->assertFalse($this->token->equals($newToken));
        });
    }
}