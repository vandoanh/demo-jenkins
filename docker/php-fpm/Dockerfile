FROM php:7.1-fpm

LABEL maintainer="Do Quyet Tien <doquyettien@gmail.com>"

# Install "curl", "libmemcached-dev", "libpq-dev", "libjpeg-dev",
#         "libpng12-dev", "libfreetype6-dev", "libssl-dev", "libmcrypt-dev",
RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y \
    sudo \
    vim \
    git \
    curl \
    libmemcached-dev \
    libz-dev \
    libpq-dev \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    libssl-dev \
    libmcrypt-dev \
    libgmp-dev \
    libzip-dev zip unzip && \
    docker-php-ext-install pdo_mysql && \
    docker-php-ext-configure zip --with-libzip && \
    docker-php-ext-install zip && \
    php -m | grep -q 'zip' \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install gmp \
    && docker-php-ext-install exif \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install mcrypt \
    && rm -rf /var/lib/apt/lists/*

###########################################################################
# Set Timezone
###########################################################################

ARG TZ=UTC
ENV TZ ${TZ}

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

###########################################################################
# PHP extension
###########################################################################

# Redis:

RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

# Memcache:

RUN curl -L -o /tmp/memcached.tar.gz "https://github.com/php-memcached-dev/php-memcached/archive/php7.tar.gz" \
    && mkdir -p memcached \
    && tar -C memcached -zxvf /tmp/memcached.tar.gz --strip 1 \
    && ( \
        cd memcached \
        && phpize \
        && ./configure \
        && make -j$(nproc) \
        && make install \
    ) \
    && rm -r memcached \
    && rm /tmp/memcached.tar.gz \
    && docker-php-ext-enable memcached

# Opcache:

RUN docker-php-ext-install opcache

# Copy opcache configration
COPY ./opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# MySQL Client:

USER root

RUN apt-get update -yqq && \
    apt-get -y install mysql-client

###########################################################################
# NodeJS
###########################################################################

RUN curl -sL https://deb.nodesource.com/setup_11.x | bash - && \
    apt-get -y install nodejs

RUN node -v && npm -v

###########################################################################
# Composer
###########################################################################

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

###########################################################################
# Adminer
###########################################################################

# RUN curl -L -o /usr/share/nginx/html/blog/adminer.php "https://github.com/vrana/adminer/releases/download/v4.7.1/adminer-4.7.1-mysql-en.php"

###########################################################################
# Crontab
###########################################################################

USER root

COPY ./crontab /etc/cron.d

RUN chmod -R 644 /etc/cron.d

###########################################################################
# Check PHP version:
###########################################################################

RUN set -xe; php -v | head -n 1 | grep -q "PHP ${PHP_VERSION}."

#
#--------------------------------------------------------------------------
# Final Touch
#--------------------------------------------------------------------------
#

COPY ./laravel.ini /usr/local/etc/php/conf.d
COPY ./php.ini /usr/local/etc/php/php.ini

USER root

# Clean up
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    rm /var/log/lastlog /var/log/faillog

RUN usermod -u 1000 www-data

WORKDIR /usr/share/nginx/html/blog

CMD ["php-fpm"]

EXPOSE 9000
