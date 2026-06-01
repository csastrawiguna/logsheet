FROM php:7.4-apache

# Pasang library zip sareng extension mysqli + zip sakaligus
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    && docker-php-ext-install mysqli zip

# Aktifkeun mod_rewrite Apache
RUN a2enmod rewrite