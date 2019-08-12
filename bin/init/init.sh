#!/bin/bash

cd ../..
# Now in project root
touch .env
cat << EOF > .env
# Docker-compose automatically searches for and uses this file (.env)
DOCUMENT_ROOT=./www
VHOSTS_DIR=./config/vhosts
# NOTE: 'LOG_DIR' used to be 'APACHE_LOG_DIR', but it was conflicting with an environment variable defined in
# /etc/apache2/envvars with the exact same name in the webserver container, which prevented the webserver from starting
LOG_DIR=./logs/apache2
PHP_INI=./config/php/php.ini
MYSQL_DATA_DIR=./data/mysql
MYSQL_LOG_DIR=./logs/mysql
WEBSERVER_NAME=webserver
# For debugging php cli scripts
PHP_IDE_CONFIG=serverName=docker

# TODO - Separate env variables needed for application from env variables needed for docker
# TODO - Create user account that limits permissions
# default for dev, change for prod
DB_ROOT_PASSWORD=rootpass
EOF

touch www/app.env
cat << EOF > www/app.env
#cryptosim env
DB_HOST=mysql
DB_NAME=crypto_sim
DB_TEST_NAME=crypto_sim_test
DB_USER=root
# NOTE: DB_PASS must match DB_ROOT_PASSWORD
# default for dev, change for prod
DB_PASS=rootpass

# Defines what environment the application is in (Can be 'prod', 'dev', or 'test')
APP_ENV=dev
EOF
