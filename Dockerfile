# Stage 1: Install PHP dependencies with Composer
FROM composer:2.7 as composer
WORKDIR /app
COPY . .
# Install --no-dev for production
RUN composer install --no-dev --no-interaction --optimize-autoloader

# Stage 2: Build frontend assets with Node
FROM node:18-alpine as node

# ✅ FIX: Install build tools (like python, make, g++) needed by some npm packages
RUN apk add --no-cache python3 make g++

WORKDIR /app
COPY . .
# Copy vendor files to satisfy scripts
COPY --from=composer /app/vendor /app/vendor
RUN npm install
RUN npm run build

# Stage 3: Create the final production image
FROM php:8.2-apache
WORKDIR /var/www/html

# Install required PHP extensions for Laravel, Neon (pgsql), Redis, Sockets, and Queues
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libpq-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install zip gd pdo pdo_pgsql bcmath pcntl sockets

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Configure Apache
COPY .docker/apache.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Copy built app files from previous stages
COPY --from=composer /app/vendor /var/www/html/vendor
COPY --from=node /app/public/build /var/www/html/public/build
COPY . /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Set up entrypoint
COPY .docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Default command (will be overridden by render.yaml)
CMD ["apache2-foreground"]
