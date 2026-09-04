FROM php:8.3-cli

# System deps
RUN apt-get update && apt-get install -y \
    git unzip curl libpq-dev libzip-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql bcmath zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install composer via official installer (more reliable than COPY --from on Render)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . .

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN composer diagnose || true \
    && composer install --no-dev --optimize-autoloader --no-interaction --no-progress --verbose \
    && test -f vendor/autoload.php && echo "=== vendor/autoload.php exists ===" && ls -lh vendor/autoload.php \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && mkdir -p database && touch database/database.sqlite || true

EXPOSE 10000

CMD sh -c "echo 'Checking vendor at runtime...'; ls -lh vendor/autoload.php || (echo 'VENDOR MISSING at runtime!' && ls -la && ls -la vendor 2>&1 | head -20); php artisan config:clear && php artisan migrate --force --seed || php artisan migrate --force; php artisan config:cache; php artisan serve --host=0.0.0.0 --port=\${PORT:-10000}"
