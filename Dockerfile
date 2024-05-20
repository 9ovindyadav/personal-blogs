FROM php:8.2.5-fpm-alpine3.16

RUN apk update && apk add --no-cache \
    git \
    curl \
    zip \
    vim \
    unzip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install pdo pdo_mysql

RUN mkdir -p /home/app && chown -R www-data:www-data /home/app

RUN mkdir -p /home/app/storage && chown -R www-data:www-data /home/app/storage

WORKDIR /home/app
