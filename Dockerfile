# syntax=docker/dockerfile:1.7

FROM node:22-bookworm-slim AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund

COPY . .
RUN npm run build

FROM php:8.4-apache-bookworm AS application

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        curl \
        libcurl4-openssl-dev \
        libicu-dev \
        libonig-dev \
        libsqlite3-dev \
        libxml2-dev \
        libzip-dev \
        unzip \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        curl \
        dom \
        intl \
        mbstring \
        opcache \
        pdo_sqlite \
        xml \
        zip \
    && a2enmod expires headers rewrite \
    && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --no-scripts \
    --prefer-dist

COPY . .
COPY --from=frontend /app/public/build ./public/build
COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php-production.ini /usr/local/etc/php/conf.d/zz-laporkupva.ini
COPY docker/entrypoint.sh /usr/local/bin/laporkupva-entrypoint

RUN composer dump-autoload --no-dev --classmap-authoritative --no-interaction \
    && sed -i 's/\r$//' /usr/local/bin/laporkupva-entrypoint \
    && chmod 0755 /usr/local/bin/laporkupva-entrypoint \
    && mkdir -p /var/lib/laporkupva \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/lib/laporkupva

ENTRYPOINT ["laporkupva-entrypoint"]
CMD ["apache2-foreground"]

