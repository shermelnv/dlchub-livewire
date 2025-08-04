FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    zip unzip curl git libpng-dev libonig-dev libxml2-dev libzip-dev supervisor \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy all source files
COPY . ./

# Optional if permissions are required
RUN chown -R www-data:www-data /var/www && chmod -R 775 storage bootstrap/cache

RUN composer global config --no-plugins allow-plugins.laravel/pint true


# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy supervisor config
COPY ./deploy/supervisord.conf /etc/supervisord.conf

# Use www-data user
USER www-data

EXPOSE 8000

# Run Supervisor (manages multiple Laravel processes)
CMD php artisan migrate --force --seed && /usr/bin/supervisord -c /etc/supervisord.conf
