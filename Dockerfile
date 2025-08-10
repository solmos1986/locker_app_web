# Use a base image with PHP-FPM, suitable for Laravel
FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    icu-dev

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql gd zip bcmath opcache intl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Optimize Laravel
RUN php artisan optimize

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage \
    && chown -R www-data:www-data /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Copy Nginx configuration
COPY .docker/nginx/default.conf /etc/nginx/conf.d/default.conf

ARG DB_CONNECTION=${DB_CONNECTION}
ARG DB_HOST=${DB_HOST}
ARG DB_PORT=${DB_PORT}
ARG DB_DATABASE=${DB_DATABASE}
ARG DB_USERNAME=${DB_USERNAME}
ARG DB_PASSWORD=${DB_PASSWORD}

# Expose port 80 for Nginx
EXPOSE 80

# Start PHP-FPM and Nginx using Supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]