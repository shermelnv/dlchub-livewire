FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    zip unzip curl git libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy composer files first for better Docker caching
COPY composer.json composer.lock ./

# Install Laravel dependencies (now includes pusher)
RUN composer install --no-dev --optimize-autoloader

# Copy the rest of the application
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www && chmod -R 775 storage bootstrap/cache

# Use www-data user
USER www-data

# Expose port (optional)
EXPOSE 8000

# Run migrations and serve the app
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000
