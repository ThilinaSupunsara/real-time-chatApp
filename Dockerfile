# Stage 1: Install PHP dependencies with Composer
FROM composer:2.7 as composer
WORKDIR /app
COPY . .
RUN composer install --no-dev --no-interaction --optimize-autoloader

# Stage 2: Build frontend assets with Node
FROM node:18 as node
WORKDIR /app
COPY . .
COPY --from=composer /app/vendor /app/vendor

# Use CI for clean, reproducible installs
RUN npm ci --legacy-peer-deps --unsafe-perm
ENV NODE_OPTIONS=--max-old-space-size=4096
RUN npm run build

# Stage 3: Create the final production image
FROM php:8.2-apache
WORKDIR /var/www/html

# Install PHP extensions
RUN apt-get update && apt-get install -y \
    libzip-dev libpng-dev libpq-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install zip gd pdo pdo_pgsql bcmath pcntl sockets

RUN pecl install redis && docker-php-ext-enable redis

# Apache config
COPY .docker/apache.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Copy built files
COPY --from=composer /app/vendor /var/www/html/vendor
COPY --from=node /app/public/build /var/www/html/public/build
COPY . /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Entrypoint
COPY .docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

CMD ["apache2-foreground"]
