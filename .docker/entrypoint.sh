#!/bin/sh
# 'set -e' දැම්මම, command එකක් fail වුනොත් script එක එතනින්ම නවතිනවා.
set -e

# Production එකට අවශ්‍ය artisan commands
echo "Running production optimizations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Database migrations run කිරීම
echo "Running database migrations..."
php artisan migrate --force

# Script එකට එන මුල් command එක (ඒ කියන්නේ "apache2-foreground") run කිරීම
exec "$@"
