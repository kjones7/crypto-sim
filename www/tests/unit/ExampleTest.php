<?php
namespace CryptoSim\Tests\Unit;

use CryptoSim\Controllers\UserController;
use CryptoSim\Models\User;

require "/var/www/html/vendor/autoload.php";

class UserControllerTest extends \Codeception\Test\Unit
{
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

    // tests
    public function testCreateAccount()
    {
        $username = 'username';
        $email = 'email@gmail.com';
        $password = 'password';
        $country = 'USA';

        $user = new User($username);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setCountry($country);

        $userController = new UserController($user);

        // TODO - Should I really test simple assertions like this?
        $this->assertTrue($user->getUsername() === $username);
        $this->assertTrue($user->getEmail() === $email);
        $this->assertTrue($user->getCountry() === $country);

        $userController->createAccount($user);
        $this->tester->seeInDatabase('users', ['username' => $username, 'email' => $email, 'password' => $password]);
    }
}