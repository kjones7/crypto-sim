<?php

namespace CryptoSim\Controllers;

use CryptoSim\Models\Database;
use CryptoSim\Models\User;

require "/var/www/html/vendor/autoload.php";

class DatabaseController {
    private $database;

    function __construct(){
        $this->database = new Database();
    }

    public function createAccount(User $user) : void {
        /* @var PDO */
        $conn = $this->database->getConn();
        $email = $user->getEmail();
        $username = $user->getUsername();
        $password = $user->getPassword();
        $country = $user->getCountry();

        $stmt = $conn->prepare(
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
}