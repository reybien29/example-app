#!/bin/sh
set -e

cd /var/www/html

# ── Fix APP_KEY if invalid (Required for Laravel 11/12) ────────────────────────
echo ">>> Validating APP_KEY..."
# A valid Laravel AES-256-CBC key is 32 bytes (44 chars base64 + 'base64:' prefix = 51 chars)
if [ -z "$APP_KEY" ] || [ "$(echo -n $APP_KEY | wc -c)" -lt 40 ]; then
    echo "WARNING: APP_KEY is missing or invalid format. Generating a temporary key..."
    export APP_KEY=$(php artisan key:generate --show --no-ansi)
    echo "Temporary APP_KEY has been set for this process."
fi

echo ">>> Ensuring storage directories exist..."
mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo ">>> Caching config and routes..."
# We must clear first to ensure the new APP_KEY is picked up
php artisan config:clear
php artisan config:cache
php artisan route:cache

echo ">>> Running migrations..."
php artisan migrate --force

echo ">>> Starting PHP-FPM..."
php-fpm --daemonize

echo ">>> Starting Nginx on port 10000..."
nginx -g "daemon off;"
