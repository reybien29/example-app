#!/bin/sh
set -e

# ── PHP-FPM Socket Directory ─────────────────────────────────────────────────
# Ensure the directory for the socket exists with correct permissions
mkdir -p /var/run
chown www-data:www-data /var/run

# ── Run Laravel startup tasks ─────────────────────────────────────────────────
cd /var/www/html

echo ">>> Caching config..."
php artisan config:cache

echo ">>> Caching routes..."
php artisan route:cache

echo ">>> Caching views..."
php artisan view:cache

echo ">>> Running migrations..."
php artisan migrate --force

echo ">>> Optimising application..."
php artisan optimize

# ── Start PHP-FPM in background, then Nginx in foreground ────────────────────
echo ">>> Starting PHP-FPM..."
php-fpm --daemonize

echo ">>> Starting Nginx..."
nginx -g "daemon off;"
