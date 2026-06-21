FROM php:8.2-fpm

# Arbeitsverzeichnis setzen
WORKDIR /var/www

# System-Abhängigkeiten installieren
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Node.js (Version 20 LTS) & NPM installieren
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# PHP-Erweiterungen installieren
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Neuesten Composer holen
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Berechtigungen anpassen
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Setze Upload-Limits für PHP
RUN echo "upload_max_filesize = 20M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 20M" >> /usr/local/etc/php/conf.d/uploads.ini

# Container als 'www' User ausführen (Sicherheit)
USER www

EXPOSE 9000
CMD ["php-fpm"]
