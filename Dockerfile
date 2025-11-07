# 1. Base Image එක (PHP 8.2 සහ Apache එක්ක)
FROM php:8.2-apache

# 2. අවශ්‍ය PHP extensions install කිරීම
RUN docker-php-ext-install pdo pdo_mysql bcmath

# 3. Apache "mod_rewrite" enable කිරීම
RUN a2enmod rewrite

# 4. Composer install කිරීම
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Working Directory එක set කිරීම
WORKDIR /var/www/html

# 6. Apache Virtual Host config file එක copy කිරීම
COPY .docker/apache.conf /etc/apache2/sites-available/000-default.conf

# 7. Project files ඔක්කොම copy කිරීම
COPY . .

# 8. Composer dependencies install කිරීම
RUN composer install --no-dev --optimize-autoloader

# 9. Storage සහ Cache වලට permissions දීම
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# 10. --- අලුතින් එකතු කළ කොටස ---
# Entrypoint script එක copy කිරීම
COPY .docker/entrypoint.sh /usr/local/bin/entrypoint.sh

# Entrypoint script එක executable (run කරන්න පුළුවන්) කිරීම
RUN chmod +x /usr/local/bin/entrypoint.sh

# Container එකේ Entrypoint එක විදියට set කිරීම
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Apache server එක run කරන base image එකේ default command එක
CMD ["apache2-foreground"]
# --- අලුත් කොටස අවසානයි ---

# 11. Port එක Expose කිරීම
EXPOSE 80
