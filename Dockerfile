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
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy existing application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node.js (for building assets)
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - && \
    apt-get install -y nodejs

# Install npm dependencies and build assets (adjust if using Vite)
RUN npm install && npm run build

# Permissions for Laravel storage and cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 9000 for php-fpm (if using nginx)
EXPOSE 8000

# Start PHP-FPM server (change as needed)
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000

