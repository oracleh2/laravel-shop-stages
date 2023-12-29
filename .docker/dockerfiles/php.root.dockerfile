FROM node:21.3.0-alpine3.18 AS nodejs
FROM php:8.2-fpm-alpine

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions

RUN mkdir -p /var/www/shop

WORKDIR /var/www/shop

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
COPY --from=nodejs /opt /opt
COPY --from=nodejs /usr/local /usr/local

RUN apk update
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
        sudo \
        zip \
        unzip \
        git \
        curl \
        mc \
        zlib \
        libpq-dev

RUN sed -i "s/user = www-data/user = root/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = root/g" /usr/local/etc/php-fpm.d/www.conf
RUN echo "php_admin_flag[log_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf

RUN install-php-extensions gd bcmath mbstring intl zip pdo_mysql mysqli pdo_pgsql pgsql
#RUN install-php-extensions imagick
#RUN install-php-extensions pdo_postgresql
#RUN install-php-extensions opcache
#RUN install-php-extensions pcntl
#RUN install-php-extensions ldap
#RUN install-php-extensions sysvmsg
#RUN install-php-extensions exif
#RUN install-php-extensions gmp

#RUN set -ex
#RUN apk --no-cache add postgresql-dev
#RUN docker-php-ext-install pdo pdo_pgsql

RUN mkdir -p /usr/src/php/ext/redis \
    && curl -L https://github.com/phpredis/phpredis/archive/5.3.4.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 \
    && echo 'redis' >> /usr/src/php-available-exts \
    && docker-php-ext-install redis

ENV COMPOSER_ALLOW_SUPERUSER=1
#RUN /usr/local/bin/composer install --optimize-autoloader

# Generate application key
#RUN php artisan key:generate

# Set execute permission for start.sh
#RUN chmod +x /var/www/shop/start.sh

USER root
RUN echo 'alias a="php artisan"' >> ~/.bashrc
RUN echo 'alias z="cd /var/www/shop"' >> ~/.bashrc
CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]
