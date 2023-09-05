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

WORKDIR /app
