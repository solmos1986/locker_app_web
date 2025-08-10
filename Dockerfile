# Stage 1: Build environment and Composer dependencies
FROM php:8.4-fpm-alpine AS builder

# Install system dependencies and PHP extensions for Laravel with common database support
RUN apk add --no-cache \
    git \
    curl \
    libzip-dev \
    libpng-dev \
    jpeg-dev \
    freetype-dev \
    postgresql-dev \
    mysql-client \
    nodejs \
    npm \
    icu-dev \
    oniguruma-dev \
    libxml2-dev \
    sqlite-dev \
    zip \
    unzip \
    nginx # For serving assets in a later stage if needed

RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql zip gd intl opcache bcmath exif pcntl sockets

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application source code
COPY . .

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Clear Laravel caches and optimize
RUN php artisan cache:clear \
    && php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear \
    && php artisan optimize

# Stage 2: Production image
FROM php:8.4-fpm-alpine

# Install necessary runtime dependencies
RUN apk add --no-cache \
    libzip \
    libpng \
    libjpeg \
    freetype \
    postgresql-libs \
    libintl \
    libxml2 \
    sqlite-libs

# Copy installed Composer dependencies and application code from the builder stage
COPY --from=builder /var/www/html /var/www/html

# Set appropriate permissions for Laravel storage and bootstrap/cache directories
RUN chown -R www-data:www-data /var/www/html/storage \
    && chown -R www-data:www-data /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

WORKDIR /var/www/html

CMD ["php-fpm"]