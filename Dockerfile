FROM php:8.3-apache

# Set working directory
WORKDIR /var/www/html

# --- System dependencies & PHP extensions ---
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    nodejs \
    npm \
    && docker-php-ext-install pdo_mysql zip \
    && docker-php-ext-enable opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# --- Enable Apache modules ---
RUN a2enmod rewrite headers expires deflate

# --- Configure Apache for Laravel ---
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf \
    && echo "<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>\n\
ServerName localhost" >> /etc/apache2/apache2.conf

# --- Copy app files ---
COPY . .

# --- Install Composer ---
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# --- Install PHP dependencies (production only) ---
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# --- Build frontend assets ---
RUN npm ci && npm run build

# --- Laravel optimization commands ---
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan event:cache && \
    php artisan optimize

# --- Fix permissions ---
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# --- OPCache optimization ---
RUN echo "\
opcache.enable=1\n\
opcache.enable_cli=1\n\
opcache.memory_consumption=256\n\
opcache.interned_strings_buffer=16\n\
opcache.max_accelerated_files=10000\n\
opcache.validate_timestamps=0\n\
opcache.save_comments=1\n\
opcache.fast_shutdown=1\n" > /usr/local/etc/php/conf.d/opcache.ini

# --- Expose port for DigitalOcean ---
EXPOSE 8080

# --- Start Apache ---
CMD ["apache2-foreground"]
