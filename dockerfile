# Utilise l'image officielle PHP 8.2 avec Apache
FROM php:8.2-apache

# Installe les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Active le module Apache rewrite (important pour Laravel)
RUN a2enmod rewrite

# Installe Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Copie tout le code source dans le conteneur
COPY . /var/www/html

# Définit le dossier de travail
WORKDIR /var/www/html

# Installe les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Donne les bonnes permissions aux dossiers nécessaires
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose le port 80 (Apache)
EXPOSE 80

# Commande de démarrage
CMD ["apache2-foreground"]