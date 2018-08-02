DROP DATABASE IF EXISTS crypto_sim;
CREATE DATABASE crypto_sim;
USE crypto_sim;

CREATE TABLE users (
    id VARCHAR(255) NOT NULL,
    nickname VARCHAR(64) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    creation_date DATETIME NOT NULL,
    failed_login_attempts INT NOT NULL DEFAULT 0,
    last_failed_login_attempt DATETIME,
    CONSTRAINT PK_users_id PRIMARY KEY (id)
);


CREATE TABLE friends (
    id VARCHAR(255) NOT NULL,
    to_user_id VARCHAR(255) NOT NULL,
    from_user_id VARCHAR(255) NOT NULL,
    date_sent DATETIME NOT NULL,
    date_replied DATETIME NULL,
    accepted TINYINT NULL,
    CONSTRAINT PK_friends_id PRIMARY KEY (id),
    CONSTRAINT FK_friends_to_user_id_users_id FOREIGN KEY (to_user_id) REFERENCES users(id),
    CONSTRAINT FK_friends_from_user_id_users_id FOREIGN KEY (from_user_id) REFERENCES users(id)
);

CREATE TABLE cryptocurrencies (
    id INT NOT NULL AUTO_INCREMENT,
    name varchar(45) NOT NULL,
    abbreviation varchar(8) NOT NULL,
    worth_in_USD DECIMAL(17,10) NOT NULL,
    CONSTRAINT PK_cryptocurrencies_id PRIMARY KEY (id)
);

CREATE TABLE portfolios (
    id VARCHAR(255) NOT NULL,
    user_id VARCHAR(255) NOT NULL,
    type ENUM('freeplay', 'group', 'competitive') NOT NULL,
    date_created DATETIME NOT NULL,
    date_deleted DATETIME,
    title varchar(60) NOT NULL,
    status ENUM('open', 'closed', 'deleted') NOT NULL,
    total_amount DECIMAL(12,2) NOT NULL DEFAULT 10000,
    resets INT,
    date_last_reset DATETIME,
    visibility ENUM('private', 'public') NOT NULL,
    duration INT,
    CONSTRAINT PK_portfolios_id PRIMARY KEY (id),
    CONSTRAINT FK_portfolios_user_id_users_id FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE transactions (
    id VARCHAR(255) NOT NULL,
    portfolio_id VARCHAR(255) NOT NULL,
    cryptocurrency_id INT NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    date DATETIME NOT NULL,
    CONSTRAINT FK_transactions_cryptocurrency_id_cryptocurrencies_id FOREIGN KEY (cryptocurrency_id) REFERENCES cryptocurrencies(id),
    CONSTRAINT FK_transactions_portfolio_id_portfolios_id FOREIGN KEY (portfolio_id) REFERENCES portfolios(id),
    CONSTRAINT PK_transactions_id PRIMARY KEY (id)
)