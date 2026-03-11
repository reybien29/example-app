#!/bin/sh
set -e

# ── PHP-FPM: listen on unix socket so Nginx can reach it ─────────────────────
sed -i 's|listen = 127.0.0.1:9000|listen = /var/run/php-fpm.sock|g' \
    /usr/local/etc/php-fpm.d/www.conf

sed -i 's|;listen.owner = www-data|listen.owner = www-data|g' \
    /usr/local/etc/php-fpm.d/www.conf

sed -i 's|;listen.group = www-data|listen.group = www-data|g' \
    /usr/local/etc/php-fpm.d/www.conf

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
