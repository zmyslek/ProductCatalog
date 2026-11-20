FROM composer:2 AS composer
FROM php:8.4-cli

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libicu-dev \
        libsqlite3-dev \
        libpq-dev \
        libonig-dev \
        libxml2-dev \
        libzip-dev \
        unzip \
        zip \
    && docker-php-ext-install -j$(nproc) \
        mbstring \
        intl \
        xml \
        zip \
        opcache \
        pdo_sqlite \
        pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY ProductCatalog ./

RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader \
    && php artisan optimize \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

RUN echo "opcache.enable=1\nopcache.memory_consumption=128\nopcache.interned_strings_buffer=16\nopcache.max_accelerated_files=20000\nopcache.validate_timestamps=0\nopcache.revalidate_freq=0" > /usr/local/etc/php/conf.d/opcache.ini

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
