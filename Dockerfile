FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    curl zip unzip git sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader

EXPOSE 8000

CMD cp .env.example .env && \
    php artisan key:generate --force && \
    mkdir -p /var/data && \
    touch /var/data/database.sqlite && \
    php artisan migrate --force && \
    (php artisan db:seed --force || echo "Seeding skipped") && \
    (php artisan storage:link || echo "Storage link skipped") && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
