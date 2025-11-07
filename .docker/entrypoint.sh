#!/bin/sh
set -e

if [ "$1" = "apache2-foreground" ]; then
    echo "Running as WEB service. Caching config and running migrations..."

    # Run artisan commands (as root)
    php artisan config:cache
    php artisan route:cache
    php artisan migrate --force

    # ✅ FIX: After commands are run, force ownership back to www-data
    # This gives Apache permission to read the new cache files.
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
else
    echo "Running as WORKER/REVERB service. Skipping setup."
fi

# Finally, execute the main command (e.g., "apache2-foreground")
exec "$@"
