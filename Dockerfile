FROM ubuntu:22.04

WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y curl unzip git php-cli php-mbstring php-xml php-bcmath php-pdo mysql-client

# Install FrankenPHP
RUN curl -sSL https://frankenphp.dev/install.sh | sh
RUN mv frankenphp /usr/local/bin/

# Copy your Laravel app
COPY . .

# Install Composer dependencies
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN composer install --optimize-autoloader --no-dev

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose App Platform port
EXPOSE 8080

# Run FrankenPHP
CMD ["frankenphp", "php-server", "-r", "public/"]
