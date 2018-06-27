DROP DATABASE IF EXISTS crypto_sim;
CREATE DATABASE crypto_sim;
USE crypto_sim;

CREATE TABLE users (
    id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(100) NOT NULL,
    username VARCHAR(64) NOT NULL,
    password VARCHAR(45) NOT NULL, -- TESTING PURPOSES ONLY, CHANGE THIS
    country VARCHAR(45),
    date_created DATETIME NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE friend_requests (
    id INT NOT NULL AUTO_INCREMENT,
    to_user_id INT NOT NULL,
    from_user_id INT NOT NULL,
    date_sent DATETIME NOT NULL,
    accepted TINYINT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (to_user_id) REFERENCES users(id),
    FOREIGN KEY (from_user_id) REFERENCES users(id)
);

CREATE TABLE friends (
    id INT NOT NULL AUTO_INCREMENT,
    user_id1 INT NOT NULL,
    user_id2 INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id1) REFERENCES users(id),
    FOREIGN KEY (user_id2) REFERENCES users(id)
);

CREATE TABLE simulations (
    id INT NOT NULL AUTO_INCREMENT,
    type ENUM('freeplay', 'group', 'competitve') NOT NULL,
    duration INT,
    date_created DATETIME NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE chat_channels (
    id INT NOT NULL AUTO_INCREMENT,
    simulation_id INT NOT NULL,
    name varchar(45) NOT NULL,
    type ENUM('public', 'private') NOT NULL,
    password varchar(45),
    PRIMARY KEY (id),
    FOREIGN KEY (simulation_id) REFERENCES simulations(id)
);

CREATE TABLE group_invites (
    id INT NOT NULL AUTO_INCREMENT,
    simulation_id INT NOT NULL,
    user_id INT NOT NULL,
    status ENUM('unseen', 'accepted', 'declined') NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (simulation_id) REFERENCES simulations(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE cryptocurrencies (
    id INT NOT NULL AUTO_INCREMENT,
    name varchar(45) NOT NULL,
    abbreviation varchar(8) NOT NULL,
    worth_in_USD DECIMAL(17,10) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE past_crypto_tables (
    id INT NOT NULL AUTO_INCREMENT,
    cryptocurrency_id INT NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (cryptocurrency_id) REFERENCES cryptocurrencies(id)
);

CREATE TABLE portfolios (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    simulation_id INT NOT NULL,
    type ENUM('freeplay', 'group', 'competitive') NOT NULL,
    date_created DATETIME NOT NULL,
    date_deleted DATETIME,
    name varchar(60) NOT NULL,
    status ENUM('open', 'closed', 'deleted') NOT NULL,
    start_amount INT NOT NULL,
    total_amount DECIMAL(12,2) NOT NULL,
    resets INT,
    date_last_reset DATETIME,
    public TINYINT NOT NULL,
    duration INT,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (simulation_id) REFERENCES simulations(id)
);

CREATE TABLE wallets (
    id INT NOT NULL AUTO_INCREMENT,
    cryptocurrency_id INT NOT NULL,
    user_id INT NOT NULL,
    portfolio_id INT NOT NULL,
    cryptocurrency_amount DECIMAL(12,2) NOT NULL,
    USD_amount DECIMAL(12,2) NOT NULL,
    date_last_transaction DATETIME NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (cryptocurrency_id) REFERENCES cryptocurrencies(id),
    FOREIGN KEY (portfolio_id) REFERENCES portfolios(id)
);

CREATE TABLE messages (
    id INT NOT NULL AUTO_INCREMENT,
    to_user_id INT,
    from_user_id INT NOT NULL,
    text varchar(500) NOT NULL,
    date_sent DATETIME NOT NULL,
    channel_id INT,
    type ENUM('public', 'private') NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (to_user_id) REFERENCES users(id),
    FOREIGN KEY (from_user_id) REFERENCES users(id),
    FOREIGN KEY (channel_id) REFERENCES chat_channels(id)
);