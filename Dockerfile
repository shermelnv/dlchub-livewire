FROM php:8.3-fpm

WORKDIR /app

# System deps
RUN apt-get update && apt-get install -y \
    zip unzip git curl libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer



# Set permissions
RUN chown -R www-data:www-data /app && chmod -R 755 /app

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Run artisan commands
RUN php artisan config:clear && php artisan config:cache && composer dump-autoload
