FROM php:8.3-apache

# Enable Apache rewrite module
RUN a2enmod rewrite

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Copy app
WORKDIR /var/www/html
COPY . .

# Set proper Apache config for Laravel
RUN echo "<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>" > /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel

# Expose port and start Apache
EXPOSE 8080
CMD ["apache2-foreground"]
