FROM php:8.2-fpm AS base

# System dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev \
    libzip-dev libicu-dev nginx supervisor \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl \
    && pecl install redis && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Node for Vite build
COPY --from=node:20 /usr/local/bin/node /usr/local/bin/node
COPY --from=node:20 /usr/local/lib/node_modules /usr/local/lib/node_modules
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm

WORKDIR /var/www/html

# ── PHP dependencies (cached layer) ──
COPY composer.json composer.lock artisan ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist
RUN composer dump-autoload --optimize --no-dev --no-scripts

# ── Frontend dependencies (cached layer) ──
COPY package.json package-lock.json ./
RUN npm ci --ignore-scripts

# ── Application code ──
COPY . .

# ── Frontend build ──
RUN npm run build

# Laravel setup
RUN mkdir -p storage/framework/{cache,sessions,testing,views} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && mkdir -p public/storage \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# ── PHP-FPM: pass env vars to Laravel ──
RUN echo "clear_env = no" > /usr/local/etc/php-fpm.d/zz-env.conf

# ── Nginx config ──
COPY docker/nginx.conf /etc/nginx/sites-available/default

# ── Supervisor config ──
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
