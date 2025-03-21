#!/bin/bash
set -e

# Instalar dependencias
composer install

# Crear el archivo .env si no existe
if [ ! -f .env ]; then
    cp .env.example .env
    chown 1000:1000 .env # Asignar permisos correctos
    php artisan key:generate
    touch /var/www/database/database.sqlite
fi

# Ejecutar migraciones
php artisan migrate:fresh --seed

# Iniciar el servidor
php artisan serve --host=0.0.0.0 --port=8000