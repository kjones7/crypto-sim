<?php

use Codeception\Specify;
use Codeception\Test\Unit;
use CryptoSim\Framework\Csrf\StoredTokenReader;
use CryptoSim\Framework\Csrf\SymfonySessionTokenStorage;
use CryptoSim\Framework\Csrf\TokenStorage;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class StoredTokenReaderTest extends Unit
{
    use Specify;
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
        $this->mockSession = new Session(new MockArraySessionStorage());
        $this->tokenStorage = new SymfonySessionTokenStorage($this->mockSession);
        $this->storedTokenReader = new StoredTokenReader($this->tokenStorage);
    }

    protected function _after()
    {
    }


    /**
     * @specify
     * @var Session
     */
    private $mockSession;
    /**
     * @specify
     * @var TokenStorage
     */
    private $tokenStorage;
    /**
     * @specify
     * @var StoredTokenReader
     */
    private $storedTokenReader;

    /**
     * @throws Exception
     */
    public function testTokenRead()
    {
        $this->specify('Reading a token that doesn\'t exist adds and returns it', function() {
            $this->tester->assertNotNull($this->storedTokenReader->read('testing'));
        });

        $this->specify('Reading a token that exists returns it', function() {
            $this->tester->assertNotNull($this->storedTokenReader->read('testing'));
        });
    }
}