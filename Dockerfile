# ============================================================
# Stage 1: Build frontend assets (Node.js + Vite 7)
# ============================================================
FROM node:22-alpine AS frontend-builder

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY vite.config.js ./
COPY resources/ ./resources/

RUN npm run build

# ============================================================
# Stage 2: Install PHP dependencies (Composer)
# ============================================================
FROM composer:2 AS composer-builder

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

COPY . .
RUN composer dump-autoload --optimize --no-dev

# ============================================================
# Stage 3: Production runtime
# ============================================================
FROM php:8.4-fpm-alpine AS runtime

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    libpq-dev \
    icu-dev \
    libzip-dev \
    oniguruma-dev \
    curl

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_pgsql \
    pgsql \
    intl \
    zip \
    mbstring \
    bcmath \
    opcache \
    pcntl

# Configure OPcache for production
RUN { \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=8'; \
    echo 'opcache.max_accelerated_files=10000'; \
    echo 'opcache.revalidate_freq=0'; \
    echo 'opcache.validate_timestamps=0'; \
    echo 'opcache.enable_cli=1'; \
    echo 'opcache.jit=1255'; \
    echo 'opcache.jit_buffer_size=100M'; \
} > /usr/local/etc/php/conf.d/opcache.ini

# Configure PHP for production
RUN { \
    echo 'upload_max_filesize=64M'; \
    echo 'post_max_size=64M'; \
    echo 'memory_limit=256M'; \
    echo 'max_execution_time=60'; \
    echo 'expose_php=Off'; \
} > /usr/local/etc/php/conf.d/production.ini

WORKDIR /var/www/html

# Copy application from composer builder
COPY --from=composer-builder /app /var/www/html

# Copy built frontend assets from frontend builder
COPY --from=frontend-builder /app/public/build /var/www/html/public/build

# Create storage directories
RUN mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Nginx configuration
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# Supervisord configuration
COPY docker/supervisord.conf /etc/supervisord.conf

# Entrypoint script
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 8080

ENTRYPOINT ["/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
