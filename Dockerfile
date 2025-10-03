FROM ghcr.io/dunglas/frankenphp:latest

WORKDIR /var/www

# Copy app files
COPY . .

# Install Composer and dependencies
RUN apt-get update && apt-get install -y git unzip \
    && composer install --optimize-autoloader --no-dev

# Set permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expose App Platform port
EXPOSE 8080

# Run command: start FrankenPHP
CMD ["frankenphp", "php-server", "-r", "public/"]
