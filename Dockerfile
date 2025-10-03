# Use PHP 8.3 FPM base image
FROM php:8.3-fpm

# Set working directory
WORKDIR /var/www

# Prevent tzdata from prompting during build
ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=Asia/Manila

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    tzdata \
    git unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    && ln -snf /usr/share/zoneinfo/$TZ /etc/localtime \
    && dpkg-reconfigure -f noninteractive tzdata \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy application code
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Set permissions for Laravel
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# Cache Laravel configuration, routes, views (production optimization)
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Expose the port for App Platform
EXPOSE 8080

# Run FrankenPHP serving the public folder
CMD ["frankenphp", "php-server", "-r", "public/"]
