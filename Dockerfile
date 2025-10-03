# Use PHP 8.3 FPM as base
FROM php:8.3-fpm

WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    tzdata git unzip curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install FrankenPHP binary
RUN curl -sSL https://frankenphp.dev/install.sh | sh \
    && mv frankenphp /usr/local/bin/

# Copy application code
COPY . .

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --optimize-autoloader --no-dev

# Set permissions for Laravel
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Laravel production optimizations
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Expose port 80 (App Platform internal)
EXPOSE 80

# Run FrankenPHP (no -p flag!)
CMD ["frankenphp", "php-server", "-r", "public/"]
