FROM php:8.1-fpm-alpine3.15

# Arguments defined in docker-compose.yml
ARG LIBRE_ACCOUNTING_DOCKERFILE_VERSION=0.1
ARG SUPPORTED_LOCALES="en_US.UTF-8"

# Add Repositories
RUN rm -f /etc/apk/repositories &&\
    echo "http://dl-cdn.alpinelinux.org/alpine/v3.15/main" >> /etc/apk/repositories && \
    echo "http://dl-cdn.alpinelinux.org/alpine/v3.15/community" >> /etc/apk/repositories

# Add Dependencies
RUN apk add --update --no-cache \
    gcc \
    g++ \
    make \
    python3 \
    supervisor \
    vim \
    bash \
    nodejs \
    npm \
    git \
    nginx

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Install PHP Extensions
RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions gd zip intl imap xsl pgsql opcache bcmath mysqli pdo_mysql redis

# Configure Extension
RUN docker-php-ext-configure \
    opcache --enable-opcache

# Installing composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN rm -rf composer-setup.php

# Clear npm proxy
RUN npm config rm proxy
RUN npm config rm https-proxy

# Setup Working Dir
WORKDIR /var/www/html

COPY . .
RUN chown -R www-data:www-data /var/www/html
USER www-data
RUN composer prod
RUN npm install
RUN npm run prod

COPY docker/files/libre-accounting-php-fpm-nginx-supervisord.sh /usr/local/bin/libre-accounting-php-fpm-nginx-supervisord.sh
COPY docker/files/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/files/html /var/www/html

USER root

EXPOSE 9000
ENTRYPOINT ["/usr/local/bin/libre-accounting-php-fpm-nginx-supervisord.sh"]
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
