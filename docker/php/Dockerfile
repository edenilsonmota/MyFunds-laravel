FROM php:8.3-fpm

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    jpegoptim optipng pngquant gifsicle \
    vim \
    git \
    curl \
    zip \
    unzip \
    libpq-dev \
    libzip-dev \
    libexif-dev \
    libonig-dev

# Define o diretório de trabalho
WORKDIR /var/www

# Limpa cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala extensões PHP
RUN docker-php-ext-install pdo pdo_pgsql mbstring zip exif pcntl
RUN docker-php-ext-install gd

# Instala o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Cria usuário para o Laravel
RUN groupadd -g 1000 www && useradd -u 1000 -ms /bin/bash -g www www

# Copia os arquivos do projeto
COPY . /var/www
COPY --chown=www:www . /var/www

# Ajusta permissões do diretório do projeto
RUN chown -R www-data:www-data /var/www
RUN chmod -R 775 /var/www

# Usa o usuário não root
USER www

# Expõe a porta padrão do PHP-FPM
EXPOSE 9000

# Comando padrão
CMD ["php-fpm"]
