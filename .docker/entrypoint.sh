#!/bin/sh
set -e

if [ "$1" = "apache2-foreground" ]; then
    echo "Running as WEB service. Caching config and running migrations..."
    php artisan config:cache
    php artisan route:cache

else
    echo "Running as WORKER/REVERB service. Skipping setup."
fi

exec "$@"
