# Stage 1: Build Stage
FROM php:8.3-cli-alpine AS build

# Install dependencies
RUN apk add --no-cache \
    bash \
    curl \
    git \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    oniguruma-dev \
    autoconf \
    g++ \
    make \
    nodejs \
    npm

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node.js 22.14.0 using nvm
ENV NVM_DIR=/root/.nvm
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.7/install.sh | bash && \
    . "$NVM_DIR/nvm.sh" && \
    nvm install 22.14.0 && \
    nvm use 22.14.0 && \
    nvm alias default 22.14.0 && \
    ln -s "$NVM_DIR/versions/node/v22.14.0/bin/node" /usr/local/bin/node && \
    ln -s "$NVM_DIR/versions/node/v22.14.0/bin/npm" /usr/local/bin/npm

# Install NPM dependencies and build assets
RUN npm ci && npm run build

# Cache Laravel config
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Stage 2: Final image
FROM php:8.3-cli-alpine

# Install runtime PHP extensions
RUN apk add --no-cache \
    libzip \
    libpng \
    oniguruma \
    php81-pdo \
    php81-pdo_mysql \
    php81-mbstring \
    php81-fileinfo \
    php81-tokenizer \
    php81-xml \
    php81-curl

# Set working directory
WORKDIR /app

# Copy built application from previous stage
COPY --from=build /app /app

# Expose port
EXPOSE 8080

# Run Laravel app
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
