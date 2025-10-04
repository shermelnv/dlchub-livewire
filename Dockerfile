# Use official PHP 8.3 image with Apache
FROM php:8.3-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql zip

# Enable Apache modules
RUN a2enmod rewrite

# Configure Apache for Laravel (public directory)
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf \
    && echo "<Directory /var/www/html/public>\n\tAllowOverride All\n\tRequire all granted\n</Directory>" >> /etc/apache2/apache2.conf

# Copy the application files
COPY . /var/www/html

# Install Composer globally
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install Laravel dependencies (production optimized)
RUN composer install --no-dev --optimize-autoloader

# Set correct permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 8080 for DigitalOcean App Platform
EXPOSE 8080

# Start Apache in the foreground
CMD ["apache2-foreground"]
