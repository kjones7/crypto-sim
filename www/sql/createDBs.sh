#!/bin/bash
# TODO - Remove all references to database password/usernames

# Create main database, create tables, create empty test database
mysql -u root -p${DB_ROOT_PASSWORD} -h tnb-mysql < sqlCreateDBs.sql

# Dump main database
mysqldump -u root -p${DB_ROOT_PASSWORD} -h tnb-mysql crypto_sim > dumps/crypto_sim-dump.sql

# Copy main database to create test database using dump
mysql -u root -p${DB_ROOT_PASSWORD} -h tnb-mysql crypto_sim_test < dumps/crypto_sim-dump.sql

# Create dump of test database to be used by testing framework to repopulate
mysqldump -u root -p${DB_ROOT_PASSWORD} -h tnb-mysql crypto_sim_test > dumps/crypto_sim_test-dump.sql