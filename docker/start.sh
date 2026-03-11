#!/bin/sh
set -e

# ── PHP-FPM Socket Directory ─────────────────────────────────────────────────
# Ensure the directory for the socket exists with correct permissions
mkdir -p /var/run
chown www-data:www-data /var/run

# ── Run Laravel startup tasks ─────────────────────────────────────────────────
cd /var/www/html

echo ">>> Validating view paths..."
if [ ! -d "/var/www/html/resources/views" ]; then
    echo "ERROR: resources/views not found!"
    exit 1
fi

echo ">>> Clearing caches..."
php artisan optimize:clear || echo ">>> Already clear."

echo ">>> Caching everything..."
# Combine config, route, and view caching
php artisan optimize || {
    echo "ERROR: Optimize failed. Falling back to individual steps..."
    php artisan config:cache || exit 1
    php artisan route:cache || exit 1
}

echo ">>> Running migrations..."
php artisan migrate --force

echo ">>> Ready to start."

# ── Start PHP-FPM in background, then Nginx in foreground ────────────────────
echo ">>> Starting PHP-FPM..."
php-fpm --daemonize

echo ">>> Starting Nginx..."
nginx -g "daemon off;"
