<?php

namespace CryptoSim\Models;
use PDO;
require "/var/www/html/vendor/autoload.php";

class Database {
    private $conn;
    private $db;

    function __construct() {
        // TODO - Use the .env file instead of the config.ini
        $db = parse_ini_file("/var/www/html/config.ini");
        // assign data from hidden_data.php to local variables
        // TODO - Figure out a better way to store and get database credentials
        // global $dbUserName, $dbUserPass, $dbName;
        // $this->dbUser = $dbUserName;
        // $this->dbPass = $dbUserPass;
        // $this->dbName = $dbName;
        // connect to DB
        $dsn = $db['type'] . ':dbname=' . $db['name'] . ';host=' . $db['host'];

        // TODO - improve this try/catch to not just echo the error
        try {
            // TODO - figure out a way to get the db variables to work here
            // for some reason the variables are blank strings//
            // try saving variables in .bashrc and getting with getenv('{NAME_OF_VARIABLE_HERE}')
            $this->conn = new PDO($dsn, $db['user'], $db['pass']);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }
    /**
     * Get the value of dbUser
     */ 
    public function getDbUser()
    {
        return $this->dbUser;
    }

    /**
     * Set the value of dbUser
     *
     * @return  self
     */ 
    public function setDbUser($dbUser)
    {
        $this->dbUser = $dbUser;

        return $this;
    }

    /**
     * Get the value of dbPass
     */ 
    public function getDbPass()
    {
        return $this->dbPass;
    }

    /**
     * Set the value of dbPass
     *
     * @return  self
     */ 
    public function setDbPass($dbPass)
    {
        $this->dbPass = $dbPass;

        return $this;
    }

    /**
     * Get the value of dbName
     */ 
    public function getDbName()
    {
        return $this->dbName;
    }

    /**
     * Set the value of dbName
     *
     * @return  self
     */ 
    public function setDbName($dbName)
    {
        $this->dbName = $dbName;

        return $this;
    }

    /**
     * Get the value of conn
     */ 
    public function getConn()
    {
        return $this->conn;
    }

    /**
     * Set the value of conn
     *
     * @return  self
     */ 
    public function setConn($conn)
    {
        $this->conn = $conn;

        return $this;
    }
}