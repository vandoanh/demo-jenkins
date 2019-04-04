FROM nginx

LABEL maintainer="Do Quyet Tien <doquyettien@gmail.com>"

ARG PHP_UPSTREAM_CONTAINER=php-fpm
ARG PHP_UPSTREAM_PORT=9000

RUN apt-get update \
    && apt-get upgrade \
    && apt-get -y install openssl \
    bash \
    vim \
    curl \
    && adduser -H -u 1000 -s /bin/bash www-data

###########################################################################
# Set Timezone
###########################################################################

ARG TZ=UTC
ENV TZ ${TZ}

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Set upstream conf and remove the default conf
RUN echo "upstream php-upstream { server ${PHP_UPSTREAM_CONTAINER}:${PHP_UPSTREAM_PORT}; }" > /etc/nginx/conf.d/upstream.conf \
    && rm /etc/nginx/conf.d/default.conf

COPY ./nginx.conf /etc/nginx/
COPY ./fastcgi.conf /etc/nginx/
COPY ./app.conf /etc/nginx/conf.d

###########################################################################
# phpMyAdmin
###########################################################################

RUN curl -L -o /tmp/phpMyAdmin.tar.gz "https://www.phpmyadmin.net/downloads/phpMyAdmin-latest-english.tar.gz" \
    && mkdir -p /usr/share/nginx/html/phpMyAdmin \
    && tar -C /usr/share/nginx/html/phpMyAdmin -zxvf /tmp/phpMyAdmin.tar.gz --strip 1 \
    && rm /tmp/phpMyAdmin.tar.gz

###########################################################################
# Adminer
###########################################################################

RUN curl -L -o /usr/share/nginx/html/adminer.php "https://github.com/vrana/adminer/releases/download/v4.7.1/adminer-4.7.1-mysql-en.php"

CMD ["nginx"]

EXPOSE 80 443 9100
