DROP DATABASE IF EXISTS crypto_sim;
CREATE DATABASE crypto_sim;
USE crypto_sim;

CREATE TABLE users (
    id VARCHAR(255) NOT NULL,
    nickname VARCHAR(64) NOT NULL,
    country VARCHAR(64) NOT NULL,
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
    CONSTRAINT PK_cryptocurrencies_id PRIMARY KEY (id)
);

CREATE TABLE cryptocurrency_prices (
    id INT NOT NULL AUTO_INCREMENT,
    cryptocurrency_id INT NOT NULL,
    worth_in_USD DECIMAL(17,8) NOT NULL,
    date_added DATETIME NOT NULL DEFAULT NOW(),
    CONSTRAINT PK_cryptocurrencies_prices_id PRIMARY KEY (id)
);


CREATE TABLE groups (
    id VARCHAR(255) NOT NULL,
    creator_user_id VARCHAR(255) NOT NULL,
    CONSTRAINT PK_groups_id PRIMARY KEY (id)
);

CREATE TABLE portfolios (
    id VARCHAR(255) NOT NULL,
    user_id VARCHAR(255) NOT NULL,
    type ENUM('freeplay', 'group', 'competitive') NOT NULL,
    date_created DATETIME NOT NULL,
    date_deleted DATETIME,
    title varchar(60) NOT NULL,
    status ENUM('open', 'closed', 'deleted') NOT NULL,
    start_amount DECIMAL(12,2) NOT NULL DEFAULT 10000,
    resets INT,
    date_last_reset DATETIME,
    visibility ENUM('private', 'public') NOT NULL,
    duration INT,
    group_id VARCHAR(255) NULL,
    CONSTRAINT PK_portfolios_id PRIMARY KEY (id),
    CONSTRAINT FK_portfolios_user_id_users_id FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT FK_portfolios_group_id_groups_id FOREIGN KEY (group_id) REFERENCES groups(id)
);

CREATE TABLE transactions (
    id VARCHAR(255) NOT NULL,
    portfolio_id VARCHAR(255) NOT NULL,
    cryptocurrency_id INT NOT NULL,
    usd_amount DECIMAL(17,8) NOT NULL,
    cryptocurrency_amount DECIMAL(17,8) NOT NULL,
    type ENUM('buy', 'sell') NOT NULL,
    date DATETIME NOT NULL,
    status ENUM('active', 'inactive') NOT NULL,
    CONSTRAINT FK_transactions_cryptocurrency_id_cryptocurrencies_id FOREIGN KEY (cryptocurrency_id) REFERENCES cryptocurrencies(id),
    CONSTRAINT FK_transactions_portfolio_id_portfolios_id FOREIGN KEY (portfolio_id) REFERENCES portfolios(id),
    CONSTRAINT PK_transactions_id PRIMARY KEY (id)
);

CREATE TABLE group_invites (
    id VARCHAR(255) NOT NULL,
    accepted BOOLEAN NULL,
    to_user_id VARCHAR(255) NOT NULL,
    group_id VARCHAR(255) NOT NULL,
    CONSTRAINT PK_group_invites_id PRIMARY KEY (id),
    CONSTRAINT FK_group_invites_user_id_users_id FOREIGN KEY (to_user_id) REFERENCES users(id),
    CONSTRAINT FK_group_invites_group_id_groups_id FOREIGN KEY (group_id) REFERENCES groups(id)
);

CREATE TABLE `sessions` (
    `sess_id` VARCHAR(128) NOT NULL PRIMARY KEY,
    `sess_data` BLOB NOT NULL,
    `sess_time` INTEGER UNSIGNED NOT NULL,
    `sess_lifetime` MEDIUMINT NOT NULL
) COLLATE utf8_bin, ENGINE = InnoDB;

# Create test database
DROP DATABASE IF EXISTS crypto_sim_test;
CREATE DATABASE crypto_sim_test;
USE crypto_sim_test;

