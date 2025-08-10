FROM php:8.3-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    unzip git libzip-dev supervisor \
    && docker-php-ext-install pdo pdo_mysql zip bcmath

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install Node.js (for npm run build)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install JS dependencies & build
RUN npm install && npm run build

# Laravel setup
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Copy supervisor config
COPY supervisord.conf /etc/supervisord.conf

EXPOSE 80 6001

# Start supervisord (runs all services)
CMD ["supervisord", "-c", "/etc/supervisord.conf"]
