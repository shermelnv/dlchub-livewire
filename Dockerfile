# Use official FrankenPHP image with PHP 8.3
FROM dunglas/frankenphp:1-php8.3

# Set your domain name or use :80 to disable HTTPS
ENV SERVER_NAME=:80
ENV SERVER_ROOT=/app/public

# Enable production PHP settings
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Copy your Laravel app
COPY . /app

# Install Composer dependencies (Laravel)
RUN composer install --no-dev --optimize-autoloader

# Build frontend if needed
RUN npm install && npm run build || true

# Set permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Expose port (DigitalOcean detects it automatically)
EXPOSE 8080

# Start FrankenPHP
CMD ["frankenphp", "run", "--port", "8080", "--root", "public"]
