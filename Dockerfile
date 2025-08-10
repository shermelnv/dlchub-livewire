# Use official PHP 8.2 image with required extensions
FROM php:8.2-fpm

# Install system dependencies & PHP extensions for Laravel
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libzip-dev \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    supervisor \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy only composer files first for caching
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node.js (for building assets)
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - && \
    apt-get install -y nodejs

# Copy the rest of the app
COPY . .

# Ensure .env is present before building assets (Railway injects at build time)
# If you have a local .env for dev, uncomment:
# COPY .env .env

# Install Node modules & build assets
RUN npm install && npm run build

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Copy Supervisor configuration
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose ports
EXPOSE 8080 6001

# Start all processes using Supervisor
CMD php artisan migrate:fresh --force --seed && /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
