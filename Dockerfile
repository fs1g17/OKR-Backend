# Use the official PHP image with CLI and extensions
FROM php:8.2-fpm-alpine

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apk add --no-cache \
    git \
    unzip \
    libpq \
    postgresql-dev \
    oniguruma-dev \
    icu-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql zip intl mbstring

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Set permissions
# RUN chown -R www-data:www-data /var/www/var

# Expose the port for PHP-FPM
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
