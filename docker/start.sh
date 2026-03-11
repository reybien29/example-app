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
    ls -la /var/www/html/resources
    exit 1
fi

echo ">>> Debugging config..."
php artisan tinker --execute="echo 'View Paths: '; print_r(config('view.paths'));"

echo ">>> Clearing caches..."
php artisan optimize:clear || echo ">>> Already clear."

echo ">>> Caching everything..."
# Try optimize, but catch view errors specifically
if ! php artisan optimize; then
    echo "WARNING: Optimize failed. Trying individual steps..."
    php artisan config:cache || exit 1
    php artisan route:cache || exit 1
    php artisan view:cache || echo "WARNING: view:cache failed, but continuing..."
fi

echo ">>> Running migrations..."
php artisan migrate --force

echo ">>> Ready to start."

# ── Start PHP-FPM in background, then Nginx in foreground ────────────────────
echo ">>> Starting PHP-FPM..."
php-fpm --daemonize

echo ">>> Starting Nginx..."
nginx -g "daemon off;"
