FROM php:8.3-cli

# System deps
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql bcmath zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Ensure .dockerignore doesn't break build - copy everything then install
COPY . .

# Debug: show what was copied
RUN ls -la && cat composer.json | head -20

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress --verbose \
    && ls -lh vendor/autoload.php \
    && chown -R www-data:www-data storage bootstrap/cache vendor 2>/dev/null || chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && mkdir -p database && touch database/database.sqlite || true

EXPOSE 10000

CMD sh -c "ls -lh vendor/autoload.php || (echo 'vendor missing!' && ls -la); php artisan config:clear && php artisan migrate --force --seed || php artisan migrate --force && php artisan config:cache && php artisan serve --host=0.0.0.0 --port=\${PORT:-10000}"
