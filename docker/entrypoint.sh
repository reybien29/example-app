#!/bin/sh
set -e

echo "==> Starting example-app deployment..."

# Create storage directories if they don't exist
mkdir -p storage/framework/cache/data \
         storage/framework/sessions \
         storage/framework/views \
         storage/logs \
         bootstrap/cache

# Set permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Create supervisor log directory
mkdir -p /var/log/supervisor

# Cache configuration for production performance
echo "==> Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Run database migrations
echo "==> Running database migrations..."
php artisan migrate --force --no-interaction

# Create storage symlink
echo "==> Creating storage link..."
php artisan storage:link --force --no-interaction 2>/dev/null || true

echo "==> Application ready! Starting services..."

exec "$@"
