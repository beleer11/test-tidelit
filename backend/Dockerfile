FROM php:8.2-fpm

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libonig-dev zlib1g-dev \
    libpng-dev libicu-dev libxml2-dev mariadb-client curl \
    && docker-php-ext-install pdo_mysql intl zip opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar dockerize
ENV DOCKERIZE_VERSION v0.7.0
RUN curl -L https://github.com/jwilder/dockerize/releases/download/$DOCKERIZE_VERSION/dockerize-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    | tar -C /usr/local/bin -xzv

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /var/www/backend

# Copiar código
COPY . /var/www/backend

# Copiar script de inicio
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Dar permisos iniciales a cache y logs
RUN chown -R www-data:www-data var/cache var/log \
    && chmod -R 775 var/cache var/log

# Exponer puerto PHP-FPM
EXPOSE 9000

# Comando por defecto
CMD ["docker-entrypoint.sh"]
