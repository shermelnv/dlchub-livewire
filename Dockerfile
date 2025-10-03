# Use PHP 8.3 FPM as base
FROM php:8.3-fpm

# Set working directory
WORKDIR /var/www

# Set timezone and prevent interactive prompts
ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=Asia/Manila

# Install dependencies
RUN apt-get update && apt-get install -y \
    tzdata git unzip curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    && ln -snf /usr/share/zoneinfo/$TZ /etc/localtime \
    && dpkg-reconfigure -f noninteractive tzdata \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install FrankenPHP binary
RUN curl -sSL https://frankenphp.dev/install.sh | sh \
    && mv frankenphp /usr/local/bin/

# Copy application code
COPY . .

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --optimize-autoloader --no-dev

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Laravel cache optimization
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Expose port 8080 (required by App Platform)
EXPOSE 8080

# Run FrankenPHP
CMD ["frankenphp", "php-server", "-r", "public/", "-p", "8080"]
