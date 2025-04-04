# Utilisez l'image officielle PHP avec FPM
FROM php:8.2-fpm

# Dépendances système
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip pdo_mysql mbstring exif pcntl bcmath

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


# Ajoutez ceci avant le WORKDIR
# COPY docker/entrypoint.sh /usr/local/bin/
# RUN chmod +x /usr/local/bin/entrypoint.sh

# ENTRYPOINT ["entrypoint.sh"]
# CMD ["php-fpm"]

# Répertoire de travail
WORKDIR /var/www

# Copier les fichiers de dépendances
COPY composer.json composer.lock ./

# Installer les dépendances PHP (sans les dépendances de développement)
RUN composer install --no-dev --no-scripts --no-autoloader --optimize-autoloader

# Copier tout le code source
COPY . .

# Après "COPY . ." dans le Dockerfile
COPY .env ./
RUN chmod 644 .env

# Générer l'autoloader optimisé
RUN composer dump-autoload --optimize

# Configurer les permissions
RUN chmod -R 777 storage bootstrap/cache