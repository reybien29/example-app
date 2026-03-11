#!/bin/sh
set -e

cd /var/www/html

echo ">>> Ensuring storage directories exist..."
mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

echo ">>> Force clearing stuck caches..."
# Physically remove cache files to ensure no "legacy" key is stuck
rm -f bootstrap/cache/config.php
rm -f bootstrap/cache/routes.php
rm -f bootstrap/cache/services.php
rm -f bootstrap/cache/packages.php

# Clear any Laravel caches
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

echo ">>> Unsetting potentially stuck env vars..."
# If APP_KEY is set in Render Dashboard, it will override render.yaml.
# We unset it here so LARAVEL_KEY (from code/yaml) can take over in config/app.php
unset APP_KEY

echo ">>> Caching fresh config..."
php artisan config:cache
php artisan route:cache

echo ">>> Running migrations..."
php artisan migrate --force

echo ">>> Starting PHP-FPM..."
php-fpm --daemonize

echo ">>> Starting Nginx on port 10000..."
nginx -g "daemon off;"
