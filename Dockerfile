# Use official PHP 8.3 FPM image
FROM php:8.3-fpm

# Set working directory
WORKDIR /var/www

# Set timezone and prevent interactive prompts
ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=Asia/Manila

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    tzdata git unzip curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    nginx supervisor \
    && ln -snf /usr/share/zoneinfo/$TZ /etc/localtime \
    && dpkg-reconfigure -f noninteractive tzdata \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Copy application code
COPY . .

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --optimize-autoloader --no-dev

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Laravel caches
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Copy Nginx config
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Copy entrypoint
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Expose the port App Platform expects
EXPOSE 8080

# Start Nginx + PHP-FPM
ENTRYPOINT ["/entrypoint.sh"]
