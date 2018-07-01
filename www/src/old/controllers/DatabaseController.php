<?php

namespace CryptoSim\Controllers;

use CryptoSim\Models\Database;
use CryptoSim\Models\User;

require "/var/www/html/vendor/autoload.php";

class DatabaseController {
    private $database;
    private $conn;

    function __construct(){
        $this->database = new Database();
        $this->conn = $this->database->getConn();
    }

    public function createAccount(User $user) : void {
        $email = $user->getEmail();
        $username = $user->getUsername();
        $password = $user->getPassword();
        $country = $user->getCountry();

        $stmt = $this->conn->prepare(
            "INSERT INTO users(email, username, password, country, date_created)"
        .   "VALUES(:email, :username, :password, :country, NOW())"
        );
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':country', $country);

        // TODO - Implement better error handling
        if(!$stmt->execute()) {
            echo "Error: " . $stmt->errorInfo()[2];
        }
    }

    public function sendFriendRequest($fromUsername, $toUsername) {
        $stmt = $this->conn->prepare(
            "UPDATE"
        );
    }

    private function validateFriendRequest($fromUsername, $toUsername) {
        $stmt = $this->conn->prepare(
            "SELECT EXISTS(SELECT * FROM friend_requests WHERE to_user_id = :toUserId AND from_user_id = :fromUserId)"
        );
        $stmt->bindParam(':toUserId', $toUser);
    }
}