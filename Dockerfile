# 1. Base Image (PHP 8.2 and Apache)
FROM php:8.2-apache

# 2. Install system dependencies (PHP, Node.js, and Supervisor)
# --- FIX: libicu-dev (intl) සහ libexif-dev (exif) එකතු කරන ලදී ---
RUN apt-get update && apt-get install -y \
    curl \
    gnupg \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    libexif-dev \
    unzip \
    supervisor \
    ca-certificates \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Install Node.js (Version 18)
# NodeSource install කරන අලුත්ම, නිවැරදි ක්‍රමය
RUN mkdir -p /etc/apt/keyrings
RUN curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg
RUN echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_18.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list
RUN apt-get update && apt-get install -y nodejs npm

# --- FIX: Update npm to fix optional dependency bug ---
RUN npm install -g npm@latest


# --- End of Node.js install ---

# 4. Install required PHP extensions
# --- FIX: gd configure කිරීම සහ mbstring (exif වලට) එකතු කිරීම ---
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo pdo_mysql bcmath zip gd mbstring exif intl

# 5. Apache "mod_rewrite" enable
RUN a2enmod rewrite

# 6. Composer install
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 7. Working Directory
WORKDIR /var/www/html

# 8. Apache Virtual Host config
COPY .docker/apache.conf /etc/apache2/sites-available/000-default.conf

# 9. Supervisor Config
COPY .docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# 10. Copy ALL project files
COPY . .

# --- BUILD STEPS ---
# 11. Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# 12. Install JS dependencies
RUN npm install

# 13. Build frontend assets
RUN npm run build
# --- END OF BUILD STEPS ---

# 14. Permissions
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# 15. Entrypoint script (මේක container එක start වෙන හැම වෙලේම run වෙනවා)
COPY .docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# 16. Default command
CMD ["/usr/bin/supervisord"]

# 17. Expose Port
EXPOSE 80
EXPOSE 8080
