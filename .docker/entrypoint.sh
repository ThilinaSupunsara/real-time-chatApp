#!/bin/sh
set -e

echo "Running production optimizations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Linking storage..."
php artisan storage:link


# --- FIX: Permissions හරිද බැලීම ---
# Apache (www-data) ට storage එකට ලියන්න permissions දීම
echo "Fixing storage permissions..."
chown -R www-data:www-data storage bootstrap/cache

# Server එක පටන් ගැනීම
exec "$@"
