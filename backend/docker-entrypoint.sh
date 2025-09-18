#!/bin/bash
set -e

echo "Esperando a que MySQL esté lista..."
dockerize -wait tcp://db:3306 -timeout 60s
echo "DB lista, ejecutando composer y migraciones..."

# Dar permisos correctos a cache y logs
chown -R www-data:www-data var/cache var/log
chmod -R 775 var/cache var/log

# Instalar dependencias PHP
composer install --no-interaction --no-dev --optimize-autoloader

# Crear base de datos y ejecutar migraciones
php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --no-interaction

# Iniciar PHP-FPM
php-fpm
