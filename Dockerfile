# Use official FrankenPHP image with PHP 8.3
FROM dunglas/frankenphp:1-php8.3

# Disable HTTPS for App Platform (we’ll use :8080)
ENV SERVER_NAME=:8080
ENV SERVER_ROOT=/app/public

# Enable PHP production settings
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip curl && \
    rm -rf /var/lib/apt/lists/*

# Copy app files
COPY . /app
WORKDIR /app

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Build frontend (optional)
RUN npm install && npm run build || true

# Set permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Expose the port DO uses
EXPOSE 8080

# Run FrankenPHP on port 8080
CMD ["frankenphp", "run", "--port", "8080", "--root", "public"]
