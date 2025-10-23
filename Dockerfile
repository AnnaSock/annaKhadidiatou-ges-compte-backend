# Utiliser PHP-FPM Alpine
FROM php:8.2-fpm-alpine

# Installer les dépendances système
RUN apk add --no-cache \
    oniguruma-dev \
    libxml2-dev \
    zip \
    libzip-dev \
    postgresql-dev \
    build-base \
    bash \
    && docker-php-ext-install pdo_pgsql mbstring xml zip bcmath

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier l'application
COPY . .

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Créer et donner les permissions aux dossiers nécessaires
RUN mkdir -p storage/logs storage/framework/views bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# Exposer le port Render

EXPOSE 9000

# Démarrer PHP-FPM
CMD ["php-fpm", "-R"]
