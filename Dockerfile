# ============================================================
# Stage 1: Install PHP dependencies with Composer
# ============================================================
FROM composer:2.7 as composer
WORKDIR /app
COPY . .
RUN composer install --no-dev --no-interaction --optimize-autoloader

# ============================================================
# Stage 2: Build frontend assets with Node (Vite)
# ============================================================
FROM node:18 as node
WORKDIR /app

COPY . .
COPY --from=composer /app/vendor /app/vendor

RUN mkdir -p public/build
COPY .env .env
RUN npm ci --legacy-peer-deps --unsafe-perm
ENV NODE_OPTIONS="--max-old-space-size=4096"
RUN npm run build || (echo "❌ Build failed, showing logs:" && cat /root/.npm/_logs/*-debug-*.log)

# ============================================================
# Stage 3: Create the final production image
# ============================================================
FROM php:8.2-apache
WORKDIR /var/www/html

# ✅ FIX: Added 'su-exec' for permissions handling
RUN apt-get update && apt-get install -y \
    libzip-dev libpng-dev libpq-dev \
    su-exec \
    && apt-get clean && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install zip gd pdo pdo_pgsql bcmath pcntl sockets

# Install and enable Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Enable Apache rewrite module (for Laravel routes)
RUN a2enmod rewrite

# Configure Apache for Render (port 10000)
RUN echo '<VirtualHost *:10000>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Render requires app to listen on port 10000
ENV PORT=10000
EXPOSE 10000

# Copy built assets and code from previous stages
COPY --from=composer /app/vendor /var/www/html/vendor
COPY --from=node /app/public/build /var/www/html/public/build
COPY . /var/www/html

# Set correct base permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Entrypoint script
COPY .docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Start Apache
CMD ["apache2-foreground"]
