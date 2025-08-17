# ./php/Dockerfile
FROM php:8.2-fpm

# Instalar extensiones necesarias para SQLite
RUN apt-get update \
    && apt-get install -y sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

RUN mkdir -p /data

RUN chmod 775 /data

# Establecer el directorio de trabajo dentro del contenedor
WORKDIR /data