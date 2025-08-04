FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    zip unzip curl git libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy app files
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www

# Use www-data user to avoid volume permission issues
USER www-data

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Switch back to root to set correct permissions
USER root
RUN chmod -R 775 storage bootstrap/cache

# Expose port (optional)
EXPOSE 8000

# Run migrations and serve app
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000
