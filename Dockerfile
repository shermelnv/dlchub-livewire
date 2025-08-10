FROM php:8.3-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    unzip git libzip-dev supervisor curl \
    && docker-php-ext-install pdo pdo_mysql zip bcmath

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install Node.js for Laravel Mix / Vite
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install JS dependencies & build assets
RUN npm install && npm run build

# Laravel optimizations
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Copy Supervisor config
COPY supervisord.conf /etc/supervisord.conf

# Expose HTTP and Reverb WebSocket ports
EXPOSE 8080 6001

ENV PORT=8080

# Start Supervisor (manages all processes)
CMD ["supervisord", "-c", "/etc/supervisord.conf"]
