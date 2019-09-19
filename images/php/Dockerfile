FROM php:7.2-fpm

RUN docker-php-ext-install mbstring tokenizer mysqli pdo_mysql
RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git zip unzip
RUN apt-get install -y --no-install-recommends libc-client-dev libkrb5-dev
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
RUN docker-php-ext-configure imap --with-imap --with-imap-ssl --with-kerberos
RUN docker-php-ext-install imap
ADD . /var/www/html
RUN chown -R www-data:www-data *
RUN chown -R 755 *