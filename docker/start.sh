#!/bin/sh
set -e

cd /var/www/html

echo ">>> Ensuring storage directories exist..."
# Explicitly create directories (avoiding brace expansion which might fail in sh)
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/logs
mkdir -p bootstrap/cache

echo ">>> Setting permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

echo ">>> Force clearing stuck caches..."
rm -f bootstrap/cache/config.php
rm -f bootstrap/cache/routes.php
rm -f bootstrap/cache/services.php
rm -f bootstrap/cache/packages.php

php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

echo ">>> Unsetting potentially stuck env vars..."
unset APP_KEY

echo ">>> Caching fresh config..."
# We use the hardcoded fallback in config/app.php or LARAVEL_KEY from render.yaml
php artisan config:cache
php artisan route:cache

echo ">>> Running migrations..."
php artisan migrate --force

echo ">>> Starting PHP-FPM..."
php-fpm --daemonize

echo ">>> Starting Nginx on port 10000..."
nginx -g "daemon off;"
