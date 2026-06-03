FROM php:7.4-apache

# 1. Pasang library sistem keur ZIP sareng GD (PHPSpreadsheet)
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# 2. Pasang extension mysqli, zip, sareng gd sakaligus
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install mysqli zip gd

# 3. Aktifkeun mod_rewrite Apache
RUN a2enmod rewrite

# 4. Setel user www-data sangkan sinkron jeung Pop!_OS maneh (nyingkahan error permission)
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

WORKDIR /var/www/html