# Start from official PHP 8.3 FPM image
FROM php:8.3-fpm

# Set working directory
WORKDIR /var/www

# Prevent interactive prompts & set timezone
ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=Asia/Manila

# Install dependencies
RUN apt-get update && apt-get install -y \
    tzdata git unzip curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    && ln -snf /usr/share/zoneinfo/$TZ /etc/localtime \
    && dpkg-reconfigure -f noninteractive tzdata \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install FrankenPHP
RUN curl -sSL https://frankenphp.dev/install.sh | sh \
    && mv frankenphp /usr/local/bin/

# Copy app code
COPY . .

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --optimize-autoloader --no-dev

# Set Laravel permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Laravel production optimizations
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Expose the port App Platform expects
EXPOSE 8080

# Run FrankenPHP on the port App Platform provides
CMD ["sh", "-c", "frankenphp php-server -r public/ -p ${PORT:-8080}"]
