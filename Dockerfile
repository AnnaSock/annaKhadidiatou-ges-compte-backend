FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    oniguruma-dev \
    libxml2-dev \
    zip \
    libzip-dev \
    postgresql-dev \
    build-base \
    && docker-php-ext-install pdo_pgsql mbstring xml zip bcmath

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader

# Créer et donner les permissions aux dossiers nécessaires
RUN mkdir -p /var/www/html/storage/logs \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/bootstrap/cache \
    && chmod -R 777 /var/www/html/storage \
    && chmod -R 777 /var/www/html/bootstrap/cache