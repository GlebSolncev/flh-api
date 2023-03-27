FROM php:8.1-fpm

WORKDIR /var/www/backend

RUN rm -rf /var/www/backend
COPY ./backend .
COPY ./.env .
COPY ./docker/services/backend/opcache.ini /usr/local/etc/php/conf.d/opcache.ini


# build-essential \
#vim \
RUN apt-get update && apt-get install -y \
    curl \
    git \
    jpegoptim optipng pngquant gifsicle \
    locales \
    unzip \
    zip




# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
#RUN docker-php-ext-install opcache
#COPY ./backend /var/www/backend/

# Install PHP extensions

# Graphics Draw
#RUN apt-get update && apt-get install -y \
#    libfreetype6-dev \
#    libjpeg62-turbo-dev \
#    libpng-dev \
#    && docker-php-ext-configure gd --with-freetype --with-jpeg \
#    && docker-php-ext-install -j$(nproc) gd

# Multibyte String
RUN apt-get update && apt-get install -y libonig-dev && docker-php-ext-install mbstring

# Miscellaneous
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install exif
RUN docker-php-ext-install pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install specific version of Node.js with npm through nvm
#SHELL ["/bin/bash", "--login", "-c"]
#RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.1/install.sh | bash
#RUN nvm install v16.17.0

# Install Cron
#RUN apt-get update && apt-get install -y cron
#RUN echo "* * * * * root php /var/www/artisan schedule:run >> /var/log/cron.log 2>&1" >> /etc/crontab
#RUN touch /var/log/cron.log

CMD ["env >> /var/www/backend/.env"]
CMD bash -c "php-fpm"