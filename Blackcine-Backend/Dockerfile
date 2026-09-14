FROM php:8.2-fpm-alpine

# Installer les dépendances système
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    icu-dev \
    linux-headers

# Installer les extensions PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd xml intl opcache

# Installer Redis extension
RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurer PHP
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

# Installer curl pour healthcheck
RUN apk add --no-cache curl

# Copier les fichiers de l'application
WORKDIR /var/www/html
COPY . .

# Installer les dépendances PHP (cache Docker optimisé)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Créer les dossiers nécessaires
RUN mkdir -p storage/framework/{cache,sessions,testing,views} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && mkdir -p public/storage

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Exposer le port HTTP (artisan serve)
EXPOSE 8000

HEALTHCHECK --interval=15s --timeout=5s --retries=5 --start-period=30s \
    CMD curl -f http://localhost:8000/up || exit 1

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
