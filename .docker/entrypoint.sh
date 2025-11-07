#!/bin/sh
set -e

if [ "$1" = "apache2-foreground" ]; then
    echo "Running as WEB service. Caching config and running migrations..."

    # ✅ FIX: Run artisan commands as the 'www-data' user
    # This ensures cache files and migrations are run by the same user as the web server.
    su-exec www-data php artisan config:cache
    su-exec www-data php artisan route:cache

else
    echo "Running as WORKER/REVERB service. Skipping setup."
fi

# Finally, execute the main command (e.g., "apache2-foreground")
exec "$@"
