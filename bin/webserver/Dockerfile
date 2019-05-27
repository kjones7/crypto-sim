# WARNING: Updating to php 7.3 breaks the zeromq install. At the time of writing this, the issue is fixed in this
# php-zmq commit: https://github.com/zeromq/php-zmq/commit/af02bde61c2a5af1ae64cac09ffe2b25237aa58e. Unfortunately,
# this commit is only found in master right now, so you may need to follow the directions for installing zeromq from
# github instead of using pecl to fix this (or hopefully a new version is released by the time you need to do this).
FROM php:7.2.18-apache
# SHELL ["/bin/bash", "-c"]
RUN apt-get -y update --fix-missing
RUN apt-get upgrade -y

# Install important libraries
RUN apt-get -y install --fix-missing apt-utils build-essential git curl libcurl3 libcurl3-dev zip
RUN apt-get install nano -y
RUN apt-get install wget -y

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install nodejs
RUN curl -sL https://deb.nodesource.com/setup_8.x |bash -
RUN apt-get install -y nodejs

# Install Grunt CLI globally
RUN npm install -g grunt-cli

# Add local composer and npm packages to PATH
RUN echo "export PATH=$PATH:/var/www/html/vendor/bin/:/var/www/html/node_modules/.bin" >> ~/.bashrc

# Install zeromq, which is used for socket connections
RUN echo "deb http://download.opensuse.org/repositories/network:/messaging:/zeromq:/release-stable/Debian_8.0/ ./" >> /etc/apt/sources.list
RUN apt-get install libzmq3-dev -y
# Be sure you have 'extension=zmq.so' in your php.ini to enable the zmq-beta extension
RUN pecl install zmq-beta

# Install xdebug
RUN pecl install xdebug-2.6.0
#RUN docker-php-ext-enable xdebug

# BEGIN Other PHP7 Extensions

RUN apt-get -y install libmcrypt-dev
RUN pecl install mcrypt-1.0.2
RUN docker-php-ext-enable mcrypt

RUN apt-get -y install libsqlite3-dev libsqlite3-0 mysql-client
RUN docker-php-ext-install pdo_mysql 
RUN docker-php-ext-install pdo_sqlite
RUN docker-php-ext-install mysqli

RUN docker-php-ext-install curl
RUN docker-php-ext-install tokenizer
RUN docker-php-ext-install json

RUN apt-get -y install zlib1g-dev
RUN docker-php-ext-install zip

RUN apt-get -y install libicu-dev
RUN docker-php-ext-install -j$(nproc) intl

RUN docker-php-ext-install mbstring

RUN apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ 
RUN docker-php-ext-install -j$(nproc) gd

# END Other PHP7 Extensions

# Enable apache modules
RUN a2enmod rewrite headers

# Change DocumentRoot (TODO - Add APACHE_DOCUMENT_ROOT to .env file eventually)
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Add custom php.ini
# COPY ./php.ini /usr/local/etc/php/