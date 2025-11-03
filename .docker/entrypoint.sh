#!/bin/sh

# Exit immediately if a command fails
set -e


# Cache configuration for performance
php artisan config:cache
php artisan route:cache

# Start the Apache web server
exec apache2-foreground
