FROM library/php:8.2.2-fpm

RUN docker-php-ext-install opcache

RUN apt-get update && \
    apt-get install -y git && \
    apt-get install -y libzip-dev && \
    apt-get install -y zip unzip


RUN pecl install xdebug && \
    docker-php-ext-enable xdebug


WORKDIR /var/www/employee_php

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY ./src ./

RUN chmod -R 777 ./log
RUN chmod -R 777 ./temp

RUN composer install

EXPOSE 9000

CMD /bin/bash -c "php-fpm"

