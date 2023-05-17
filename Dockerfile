FROM php:8.2-fpm

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

WORKDIR /var/www

COPY php.ini /usr/local/etc/php/conf.d/custom-php.ini