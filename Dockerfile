# Use the official FrankenPHP image (includes PHP + Caddy)
FROM dunglas/frankenphp:1-php8.3

# Set working directory
WORKDIR /app

# Copy all project files
COPY . .

# Install dependencies
RUN apt-get update && apt-get install -y unzip git \
 && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
 && composer install --no-dev --optimize-autoloader \
 && php artisan config:cache \
 && php artisan route:cache \
 && php artisan view:cache \
 && php artisan storage:link

# Expose the port FrankenPHP will serve on
EXPOSE 8080

# Start FrankenPHP (serves Laravel from the public/ directory)
CMD ["php-server", "--root", "public", "--port", "${PORT:-8080}", "--workers", "4"]
