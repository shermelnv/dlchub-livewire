FROM php:8.3-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libonig-dev libxml2-dev zip \
    supervisor \
    && docker-php-ext-install pdo_mysql

# Set working directory
WORKDIR /var/www

# Copy files
COPY . .

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Copy your actual .env (important!)


# Laravel setup
RUN php artisan config:clear \
    && php artisan config:cache \
    && php artisan migrate --force \
    && php artisan db:seed --force

# Supervisor config
COPY deploy/supervisord.conf /etc/supervisord.conf

# Start supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
