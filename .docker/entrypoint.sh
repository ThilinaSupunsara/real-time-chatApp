#!/bin/sh
set -e

if [ "$1" = "apache2-foreground" ]; then
    echo "Running as WEB service. Caching config and running migrations..."

    # ✅ FIX: Run artisan commands as the 'www-data' user
    # This creates cache files with the correct owner.
    su -s /bin/sh -c "php artisan config:cache" www-data
    su -s /bin/sh -c "php artisan route:cache" www-data

else
    echo "Running as WORKER/REVERB service. Skipping setup."
fi

# Finally, execute the main command (e.g., "apache2-foreground")
exec "$@"
