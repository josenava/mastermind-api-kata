FROM php:7.2-fpm

LABEL author="Jose Navarrete"

ARG UID
EXPOSE $UID

RUN adduser -u  ${UID} --disabled-password --gecos "" appuser \
    && mkdir -p /appdata/www/mastermind-app \
    && chown -R appuser:appuser /appdata/www/mastermind-app

RUN apt-get update \
    && apt-get install -y git curl vim zip unzip zlib1g-dev \
    && docker-php-ext-install zip pdo pdo_mysql \
    && docker-php-ext-enable zip pdo pdo_mysql

## install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /appdata/www/mastermind-app