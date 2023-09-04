FROM php:8.2-fpm

LABEL maintainer="Евгений Панов <panov-eo@yandex.ru>"

COPY ./composer.phar /bin/composer

RUN apt-get update  \
    && apt-get install -y  \
    build-essential  \
    libpng-dev  \
    libonig-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    curl \
    git  \
    zip  \
    unzip



RUN docker-php-ext-install pdo pdo_mysql sockets

## устанавлием иструменты для работы с ClickHouse
#RUN pecl install SeasClick \
#    && mv /usr/local/etc/php/php.ini-development && mv /usr/local/etc/php/php.ini \
#    && echo "extension=SeasClick.so" >> /usr/local/etc/php/php.ini
##    && mv php.ini-production php.ini


WORKDIR /app
