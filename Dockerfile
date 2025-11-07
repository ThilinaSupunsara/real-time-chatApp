# Stage 1: Install PHP dependencies with Composer
FROM composer:2.7 as composer
WORKDIR /app
COPY . .
RUN composer install --no-dev --no-interaction --optimize-autoloader

# Stage 2: Build frontend assets with Node
FROM node:18-alpine as node
RUN apk add --no-cache python3 make g++

WORKDIR /app
COPY . .
COPY --from=composer /app/vendor /app/vendor

# ✅ FIX 1: Give Node.js more memory for the build process
ENV NODE_OPTIONS=--max-old-space-size=4096

# ✅ FIX 2: Keep the legacy flag to get past dependency errors
RUN npm install --legacy-peer-deps
RUN npm run build

# Stage 3: Create the final production image
FROM php:8.2-apache
WORKDIR /var/www/html

# Install required PHP extensions
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libpq-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install zip gd pdo pdo_pgsql bcmath pcntl sockets

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

CMD ["apache2-foreground"]
