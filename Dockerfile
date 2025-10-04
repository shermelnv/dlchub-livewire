# Base PHP
FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git curl libzip-dev zip unzip libpng-dev libonig-dev libxml2-dev supervisor nginx \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# PHP deps
RUN composer install --no-dev --optimize-autoloader

# Node.js + build assets
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - && apt-get install -y nodejs
RUN npm install && npm run build

# Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Supervisor config
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Nginx config
COPY nginx.conf /etc/nginx/sites-enabled/default

# Expose internal ports
EXPOSE 8000 

# Start Supervisor (Laravel + Reverb + Scheduler)
CMD php artisan migrate --force && /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
  