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
    PRIMARY KEY (auto_inc_id)
);