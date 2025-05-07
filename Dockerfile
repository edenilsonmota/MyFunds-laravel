# Etapa 1: Build frontend com Vite
FROM node:20 AS node
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Etapa 2: PHP com Composer
FROM php:8.3-fpm
WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpq-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY --from=node /app/public ./public
COPY . .

RUN composer install
