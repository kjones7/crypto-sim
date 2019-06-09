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

# TODO - Separate env variables needed for application from env variables needed for docker
# TODO - Create user account that limits permissions
DB_ROOT_PASSWORD=

#cryptosim env
DB_HOST=mysql
DB_NAME=crypto_sim
DB_USER=root
# NOTE: DB_PASS must match DB_ROOT_PASSWORD
DB_PASS=
EOF
