FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    curl zip unzip git sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader

COPY start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 8000

CMD ["/start.sh"]
