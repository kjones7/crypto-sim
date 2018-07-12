DROP DATABASE IF EXISTS crypto_sim;
CREATE DATABASE crypto_sim;
USE crypto_sim;

CREATE TABLE users (
    auto_inc_id INT NOT NULL AUTO_INCREMENT,
    id VARCHAR(255) NOT NULL,
    nickname VARCHAR(64) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    creation_date DATETIME NOT NULL,
    failed_login_attempts INT NOT NULL DEFAULT 0,
    last_failed_login_attempt DATETIME,
    PRIMARY KEY (auto_inc_id),
    UNIQUE (id)
);

-- CREATE TABLE friend_requests (
--     auto_inc_id INT NOT NULL AUTO_INCREMENT,
--     id VARCHAR(255) NOT NULL,
--     to_user_id VARCHAR(255) NOT NULL,
--     from_user_id VARCHAR(255) NOT NULL,
--     date_sent DATETIME NOT NULL,
--     accepted TINYINT NOT NULL,
--     PRIMARY KEY (auto_inc_id),
--     UNIQUE (id),
--     FOREIGN KEY (to_user_id) REFERENCES users(id),
--     FOREIGN KEY (from_user_id) REFERENCES users(id)
-- );

CREATE TABLE friends (
    auto_inc_id INT NOT NULL AUTO_INCREMENT,
    id VARCHAR(255) NOT NULL,
    to_user_id VARCHAR(255) NOT NULL,
    from_user_id VARCHAR(255) NOT NULL,
    date_sent DATETIME NOT NULL,
    date_replied DATETIME NULL,
    accepted TINYINT NULL,
    PRIMARY KEY (auto_inc_id),
    UNIQUE (id),
    FOREIGN KEY (to_user_id) REFERENCES users(id),
    FOREIGN KEY (from_user_id) REFERENCES users(id)
);