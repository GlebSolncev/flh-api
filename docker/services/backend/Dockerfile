FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    curl \
    git \
    jpegoptim optipng pngquant gifsicle \
    locales \
    unzip \
    zip \
    build-essential

# Ext install
RUN apt-get update && apt-get install -y libonig-dev &&  \
    docker-php-ext-install mbstring && \
    docker-php-ext-install bcmath && \
    docker-php-ext-install exif && \
    docker-php-ext-install pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/backend

COPY ./backend .
COPY .env ./
CMD bash -c "php-fpm"

