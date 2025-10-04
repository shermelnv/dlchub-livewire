FROM php:8.3-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql zip

# Enable Apache rewrite module
RUN a2enmod rewrite

# Configure Apache for Laravel
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf \
    && echo "<Directory /var/www/html/public>\n\tAllowOverride All\n\tRequire all granted\n</Directory>" >> /etc/apache2/apache2.conf

# Copy your Laravel app (entire project, not just public)
COPY . /var/www/html

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install dependencies (production optimized)
RUN composer install --no-dev --optimize-autoloader

# Set folder permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 8080 for DigitalOcean
EXPOSE 8080

# Start Apache
CMD ["apache2-foreground"]
