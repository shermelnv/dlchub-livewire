FROM php:8.3-fpm

# Install system deps
RUN apt-get update && apt-get install -y \
    git curl unzip libzip-dev libpng-dev libonig-dev libxml2-dev zip npm

# PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy composer files and install
COPY composer.json composer.lock ./
RUN composer install --optimize-autoloader --no-dev --ignore-platform-reqs

# Copy rest of app
COPY . .

# NPM install and build
RUN npm install && npm run build

# Permissions
RUN chown -R www-data:www-data /var/www

EXPOSE 9000
CMD ["php-fpm"]
