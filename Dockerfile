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

# Set working directory
WORKDIR /var/www/html

# Copy Laravel app
COPY . /var/www/html

# Point Apache to Laravel's public folder
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf \
    && echo "<Directory /var/www/html/public>\n\tAllowOverride All\n\tRequire all granted\n</Directory>" >> /etc/apache2/apache2.conf

# Enable .htaccess (important for Laravel routes)
RUN a2enmod rewrite
# Set working directory
WORKDIR /var/www/html

# Copy Laravel app
COPY . /var/www/html

# Point Apache to Laravel's public folder
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf \
    && echo "<Directory /var/www/html/public>\n\tAllowOverride All\n\tRequire all granted\n</Directory>" >> /etc/apache2/apache2.conf

# Enable .htaccess (important for Laravel routes)
RUN a2enmod rewrite

CMD ["apache2-foreground"]
