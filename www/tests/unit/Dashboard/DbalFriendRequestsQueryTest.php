<?php

use Codeception\Specify;
use Codeception\Test\Unit;
use CryptoSim\Framework\Csrf\Token;
use josegonzalez\Dotenv\Loader;
use Tracy\Debugger;

class DbalFriendRequestsQueryTest extends Unit
{
    use Specify;
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

    public function test()
    {
        // TODO - Create test database and use those credentials
        $dbURL = 'mysql://root:tiger@mysql/crypto_sim';
        $databaseUrl = new \CryptoSim\Framework\Dbal\DatabaseUrl($dbURL);

        $connectionFactory = new \CryptoSim\Framework\Dbal\ConnectionFactory($databaseUrl);
        $connection = $connectionFactory->create();
    }
}