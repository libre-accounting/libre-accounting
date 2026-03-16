FROM node:24-alpine AS node

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm install
COPY resources resources
COPY public public
COPY webpack.mix.js tailwind.config.js presets.js ./
RUN npm run production

FROM php:8.1-fpm-alpine3.15 AS php

# Arguments defined in docker-compose.yml
ARG LIBRE_ACCOUNTING_DOCKERFILE_VERSION=0.1
ARG SUPPORTED_LOCALES="en_US.UTF-8"

# Add Repositories
RUN rm -f /etc/apk/repositories &&\
    echo "http://dl-cdn.alpinelinux.org/alpine/v3.15/main" >> /etc/apk/repositories && \
    echo "http://dl-cdn.alpinelinux.org/alpine/v3.15/community" >> /etc/apk/repositories

# Add Dependencies
RUN apk add --update --no-cache \
    nano \
    bash \
    nginx

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Install PHP Extensions
RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions gd zip intl imap xsl pgsql opcache bcmath mysqli pdo_mysql

# Configure Extension
RUN docker-php-ext-configure \
    opcache --enable-opcache

# Setup Working Dir
WORKDIR /var/www/html

# Install PHP dependencies first so this layer is cached across source changes
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader --prefer-dist --no-interaction
# Now copy the application source and finish the composer setup
COPY . /var/www/html
RUN composer dump-autoload --optimize
# Copy node dependencies
COPY --from=node /app/public/js /var/www/html/public/js

COPY docker/files/libre-accounting-php-fpm-nginx.sh /usr/local/bin/libre-accounting-php-fpm-nginx.sh
COPY docker/files/php-fpm-tuning.conf /usr/local/etc/php-fpm.d/zz-tuning.conf
COPY docker/files/php.ini /usr/local/etc/php/php.ini
# Ship our nginx config: it runs the worker as www-data and buffers request
# bodies under /tmp. The stock config's default client_body_temp_path
# (/var/lib/nginx/tmp) is not writable by www-data, so file-upload POSTs failed
# with "open() ... Permission denied" and nginx returned 500 before PHP ran.
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
RUN mkdir -p /tmp/nginx_client_body /tmp/nginx_proxy /tmp/nginx_fastcgi \
 && chown -R www-data:www-data /tmp/nginx_client_body /tmp/nginx_proxy /tmp/nginx_fastcgi
COPY docker/files/html /var/www/html

# Set ownership/permissions once at build time (cached layer) instead of on
# every container start.
RUN chown -R www-data:root /var/www/html \
 && chmod -R u=rwX,g=rX,o=rX /var/www/html

EXPOSE 9000
ENTRYPOINT ["/usr/local/bin/libre-accounting-php-fpm-nginx.sh"]
CMD ["--start"]