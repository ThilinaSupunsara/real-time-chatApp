#!/bin/sh
set -e

# This entrypoint script will run for all services (web, reverb, queue)
# We use the passed-in command ($1) to determine which service this is.

if [ "$1" = "apache2-foreground" ]; then
    # This is the WEB service
    echo "Running as WEB service. Caching config and running migrations..."
    php artisan config:cache
    php artisan route:cache

else
    # This is a WORKER or REVERB service
    echo "Running as WORKER/REVERB service. Skipping setup."
fi

# Execute the command specified in render.yaml (e.g., "apache2-foreground")
exec "$@"
