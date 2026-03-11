#!/bin/sh
set -e

cd /var/www/html

echo ">>> Ensuring storage directories exist..."
mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo ">>> Caching config and routes..."
php artisan config:cache
php artisan route:cache

echo ">>> Running migrations..."
php artisan migrate --force

echo ">>> Starting PHP-FPM..."
php-fpm --daemonize

echo ">>> Starting Nginx on port 10000..."
nginx -g "daemon off;"
