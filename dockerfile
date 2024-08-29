# Utiliser une image officielle PHP 8.2.13 comme base
FROM php:8.2.13

# Mettre à jour les paquets disponibles et installer les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zlib1g-dev \
    unzip \
    libicu-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libldap2-dev \
    libxml2-dev \
    libpq-dev \
    libonig-dev \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Installer PHP PEAR et MongoDB via PECL
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Activer les extensions PHP nécessaires pour Symfony
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    gd \
    intl \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    opcache \
    zip \
    ldap \
    soap

# Configurer les fichiers CA pour cURL et OpenSSL
RUN echo "curl.cainfo=/etc/ssl/certs/ca-certificates.crt" >> /usr/local/etc/php/php.ini \
    && echo "openssl.cafile=/etc/ssl/certs/ca-certificates.crt" >> /usr/local/etc/php/php.ini

# Définir le répertoire de travail dans le conteneur
WORKDIR /var/www/html

# Copier les fichiers du projet Symfony dans le conteneur
COPY . .

# Installer les dépendances du projet Symfony avec Composer
RUN composer install --no-scripts --no-autoloader

# Exposer le port 8000 pour l'accès au serveur web de développement Symfony
EXPOSE 8000

# Lancer le serveur web de développement Symfony sans TLS
CMD ["symfony", "serve", "--port=8000", "--no-tls"]
