#!/bin/bash
set -e

mkdir -p /var/data
touch /var/data/database.sqlite

cat > /app/.env << EOF
APP_NAME=OdaPOS
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://odaai-pos-backend.onrender.com

DB_CONNECTION=sqlite
DB_DATABASE=/var/data/database.sqlite

LOG_CHANNEL=stderr
SESSION_DRIVER=file
SESSION_LIFETIME=120
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
EOF

php artisan key:generate --force
php artisan migrate --force
php artisan db:seed --force || echo "Seeding skipped"
php artisan storage:link || echo "Storage link skipped"
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
