# Stage 1: Build App
FROM php:8.2-fpm-alpine AS app

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
    bash \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libxpm-dev \
    freetype-dev \
    libxml2-dev \
    oniguruma-dev \
    icu-dev \
    g++ \
    make \
    autoconf \
    zlib-dev \
    mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo_mysql mbstring tokenizer xml intl gd bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy app files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions (for Laravel storage and cache)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Laravel app cache clearing
RUN php artisan config:clear && php artisan route:clear && php artisan view:clear
RUN php artisan event:cache

# Stage 2: Final Runtime Image
FROM php:8.2-fpm-alpine

COPY --from=app /var/www/html /var/www/html

WORKDIR /var/www/html

# Set permissions again
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Run Laravel queue + scheduler + websocket using supervisord
COPY deploy/supervisord.conf /etc/supervisord.conf

# Install supervisord
RUN apk add --no-cache supervisor

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
