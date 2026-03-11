# ──────────────────────────────────────────────────────────────────────────────
# Stage 1 – Node: compile Vite / Tailwind CSS assets
# ──────────────────────────────────────────────────────────────────────────────
FROM node:22-alpine AS node-build

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund

COPY vite.config.js ./
COPY resources/ resources/

RUN npm run build

# ──────────────────────────────────────────────────────────────────────────────
# Stage 2 – PHP-FPM + Nginx: serve the Laravel application
# ──────────────────────────────────────────────────────────────────────────────
FROM php:8.4-fpm-alpine AS app

LABEL maintainer="reybien29"

# ── System dependencies ───────────────────────────────────────────────────────
RUN apk add --no-cache \
        nginx \
        curl \
        libpq-dev \
        libzip-dev \
        oniguruma-dev \
        nodejs \
        npm \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        pgsql \
        zip \
        mbstring \
        opcache \
    && rm -rf /var/cache/apk/*

# ── Composer ──────────────────────────────────────────────────────────────────
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ── Application code ──────────────────────────────────────────────────────────
WORKDIR /var/www/html

COPY . .

# Copy compiled assets from the Node stage
COPY --from=node-build /app/public/build public/build

# Install PHP dependencies (production only, no dev tools)
RUN composer install \
        --no-dev \
        --no-interaction \
        --prefer-dist \
        --optimize-autoloader

# ── Directory permissions ─────────────────────────────────────────────────────
RUN mkdir -p storage/framework/{sessions,views,cache} \
             storage/logs \
             bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# ── Nginx configuration ───────────────────────────────────────────────────────
COPY docker/nginx.conf /etc/nginx/nginx.conf

# ── PHP opcache tuning ────────────────────────────────────────────────────────
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=10000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.revalidate_freq=0" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini

# ── Entrypoint ────────────────────────────────────────────────────────────────
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 8080

CMD ["/usr/local/bin/start.sh"]
