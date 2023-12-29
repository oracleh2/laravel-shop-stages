FROM php:8.2-fpm

RUN apt-get -y update
#RUN apt install mc -y
RUN apt install -y \
    mc \
    curl \
    gpg \
    build-essential \
    openssl \
    locate \
    software-properties-common \
    sudo \
    git \
    apt-utils \
    zip unzip \
    libpq-dev \
    libpng-dev \
    libzip-dev \
    libfreetype6-dev \
    libwebp-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libssl-dev \
    libgmp-dev \
    libldap2-dev \
    libzip-dev


RUN apt-get clean
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

#RUN wget http://www.ijg.org/files/jpegsrc.v9.tar.gz
#RUN tar xvfz jpegsrc.v9.tar.gz
#RUN cd jpeg-9
#RUN ./configure
#RUN make
#RUN make install

#RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg=/usr/local/lib
RUN docker-php-ext-install gd pdo pdo_mysql zip gmp bcmath pcntl ldap sysvmsg exif mysqli opcache
RUN docker-php-ext-enable mysqli opcache

COPY ./.docker/app/php.ini /usr/local/etc/php/conf.d/php.ini

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- \
    --filename=composer \
    --install-dir=/usr/local/bin \

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg;
RUN echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_20.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list;
RUN apt-get update && apt-get install -y nodejs;


WORKDIR /var/www/shop

COPY . /var/www/shop

RUN composer install --no-dev --optimize-autoloader
RUN npm install
RUN echo "alias a='php artisan'" >> /root/.bashrc
RUN chmod -R 777 /var/www/shop/storage



