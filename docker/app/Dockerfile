FROM php:8.2-fpm

LABEL maintainer="Евгений Панов <panov-eo@yandex.ru>"

RUN echo "alias ll='ls -Fal --color'" | tee /root/.bashrc /root/.zshrc > /dev/null

COPY ./composer.phar /bin/composer

RUN apt-get update  \
    && apt-get install -y  \
    build-essential  \
    libpng-dev  \
    libonig-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    zsh  \
    vim \
    curl \
    git  \
    zip  \
    unzip


RUN docker-php-ext-install pdo pdo_mysql php-sockets

WORKDIR /app


