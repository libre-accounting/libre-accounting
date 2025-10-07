FROM node:24-alpine AS node

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm install
COPY resources resources
COPY public public
COPY webpack.mix.js tailwind.config.js presets.js ./
RUN npm run production

FROM php:8.1-apache AS php

ARG AKAUNTING_DOCKERFILE_VERSION=0.1
ARG SUPPORTED_LOCALES="en_US.UTF-8"

RUN apt-get update \
 && apt-get -y upgrade --no-install-recommends \
 && apt-get install -y \
    build-essential \
    imagemagick \
    libfreetype6-dev \
    libicu-dev \
    libjpeg62-turbo-dev \
    libjpeg-dev \
    libmcrypt-dev \
    libonig-dev \
    libpng-dev \
    libpq-dev \
    libssl-dev \
    libxml2-dev \
    libxrender1 \
    libzip-dev \
    locales \
    openssl \
    unzip \
    zip \
    zlib1g-dev \
    --no-install-recommends \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN for locale in ${SUPPORTED_LOCALES}; do \
    sed -i 's/^# '"${locale}/${locale}/" /etc/locale.gen; done \
 && locale-gen

RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
 && docker-php-ext-install -j$(nproc) \
    gd \
    bcmath \
    intl \
    mbstring \
    pcntl \
    pdo \
    pdo_mysql \
    zip \
 && pecl install redis \
 && docker-php-ext-enable redis

COPY . /var/www/html
# Install PHP dependencies
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
RUN composer install
# Copy node dependencies
COPY --from=node /app/public/js /var/www/html/public/js

COPY docker/files/akaunting.sh /usr/local/bin/akaunting.sh
COPY docker/files/html /var/www/html

ENTRYPOINT ["/usr/local/bin/akaunting.sh"]
CMD ["--start"]
