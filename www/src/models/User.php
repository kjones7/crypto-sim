<?php
namespace CryptoSim\Models;

class User {
    private $email;
    private $username;
    private $firstName; // TODO - Add to database table
    private $lastName;  // TODO - Add to database table
    private $password;
    private $friends;
    private $country;
    private $securityQuestion;
    private $securityQuestions;
    private $friendRequests;
    private $sentMessages;
    private $receivedMessages;
    private $portfolios;
    private $database;

    function __construct($username) {
        $this->username = $username;
    }
    

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of username
     */ 
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */ 
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of firstName
     */ 
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set the value of firstName
     *
     * @return  self
     */ 
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get the value of lastName
     */ 
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set the value of lastName
     *
     * @return  self
     */ 
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of friends
     */ 
    public function getFriends()
    {
        return $this->friends;
    }

    /**
     * Set the value of friends
     *
     * @return  self
     */ 
    public function setFriends($friends)
    {
        $this->friends = $friends;

        return $this;
    }

    /**
     * Get the value of country
     */ 
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set the value of country
     *
     * @return  self
     */ 
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get the value of securityQuestion
     */ 
    public function getSecurityQuestion()
    {
        return $this->securityQuestion;
    }

    /**
     * Set the value of securityQuestion
     *
     * @return  self
     */ 
    public function setSecurityQuestion($securityQuestion)
    {
        $this->securityQuestion = $securityQuestion;

        return $this;
    }

    /**
     * Get the value of securityQuestions
     */ 
    public function getSecurityQuestions()
    {
        return $this->securityQuestions;
    }

    /**
     * Set the value of securityQuestions
     *
     * @return  self
     */ 
    public function setSecurityQuestions($securityQuestions)
    {
        $this->securityQuestions = $securityQuestions;

        return $this;
    }

    /**
     * Get the value of friendRequests
     */ 
    public function getFriendRequests()
    {
        return $this->friendRequests;
    }

    /**
     * Set the value of friendRequests
     *
     * @return  self
     */ 
    public function setFriendRequests($friendRequests)
    {
        $this->friendRequests = $friendRequests;

        return $this;
    }

    /**
     * Get the value of sentMessages
     */ 
    public function getSentMessages()
    {
        return $this->sentMessages;
    }

    /**
     * Set the value of sentMessages
     *
     * @return  self
     */ 
    public function setSentMessages($sentMessages)
    {
        $this->sentMessages = $sentMessages;

        return $this;
    }

    /**
     * Get the value of receivedMessages
     */ 
    public function getReceivedMessages()
    {
        return $this->receivedMessages;
    }

    /**
     * Set the value of receivedMessages
     *
     * @return  self
     */ 
    public function setReceivedMessages($receivedMessages)
    {
        $this->receivedMessages = $receivedMessages;

        return $this;
    }

    /**
     * Get the value of portfolios
     */ 
    public function getPortfolios()
    {
        return $this->portfolios;
    }

    /**
     * Set the value of portfolios
     *
     * @return  self
     */ 
    public function setPortfolios($portfolios)
    {
        $this->portfolios = $portfolios;

        return $this;
    }

    /**
     * Get the value of database
     */ 
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * Set the value of database
     *
     * @return  self
     */ 
    public function setDatabase($database)
    {
        $this->database = $database;

        return $this;
    }
}