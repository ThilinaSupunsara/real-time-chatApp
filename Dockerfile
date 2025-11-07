# 1. Base Image (PHP 8.2 and Apache)
FROM php:8.2-apache

# 2. Install system dependencies for PHP extensions
# We add this step to install libraries like libzip-dev, libpng-dev, etc.
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Install required PHP extensions
# We add zip, gd, exif, and intl
RUN docker-php-ext-install pdo pdo_mysql bcmath zip gd exif intl

# 4. Apache "mod_rewrite" enable
RUN a2enmod rewrite

# 5. Composer install
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Working Directory
WORKDIR /var/www/html

# 7. Apache Virtual Host config
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# 8. Copy project files
COPY . .

# 9. Composer dependencies
# This is the command that was failing. It should work now.
RUN composer install --no-dev --optimize-autoloader

# 10. Permissions
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# 11. Entrypoint script
COPY .docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# 12. Default command to run
CMD ["apache2-foreground"]

# 13. Expose Port
EXPOSE 80
