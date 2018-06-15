<?php
namespace CryptoSim\Controllers;

use CryptoSim\Models\User;
use CryptoSim\Controllers\DatabaseController;

require "/var/www/html/vendor/autoload.php";

class UserController {
    private $user;
    private $databaseController;

    function __construct(User $user) {
        $this->user = $user;
        $this->databaseController = new DatabaseController();
    }
    
    public function createAccount(User $user) {
        $this->databaseController->createAccount($user);
    }

    public function login(string $username, string $password){

    }

    public function sendFriendRequest(string $username) {

    }

    public function addFriend(string $username) {

    }

    public function declineFriendRequest(string $username) {

    }

    public function removeFriend(string $username) {

    }

    public function changePassword(string $oldPassword, string $newPassword) {

    }

    public function changeCountry(string $newCountry) {

    }

    public function sendPrivateMessage(/* Message $message */) {
        // TODO - implement Message class
    }

    public function sendPublicMessage(/* Message $message */) {
        // TODO - implement Message class
    }

    public function deleteMessage(/* Message $message */) {
        // TODO - implement Message class
    }

    private function validateDeleteMessage(/* Message $message */) {
        // TODO - implement Message class
    }
    private function validateSendPrivateMessage(/* Message $message */) {
        // TODO - implement Message class
    }

    private function validateSendPublicMessage(/* Message $message */) {
        // TODO - implement Message class
    }

    private function validateCountryInput(string $newCountry) {

    }

    private function validatePasswordChange(string $oldPassword, string $newPassword) {

    }

    private function checkSecurityQuestionAnswer(string $securityQuestionAnswer) {

    }

    private function validateFriendRequest(string $username) {

    }

    private function isLoginValid(string $username, string $password) : boolean {

    }

    private function isPasswordString(string $password) : boolean {

    }
    private function doesAccountAlreadyExist(string $username) : boolean {

    }
}