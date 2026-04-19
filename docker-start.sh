#!/bin/bash
set -e

cat > /app/.env << EOF
APP_NAME="HV Pet Shop"
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL:-http://localhost}
DB_CONNECTION=mysql
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}
SESSION_DRIVER=file
CACHE_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
LOG_CHANNEL=stderr
FILESYSTEM_DISK=local
EOF

# Copy ảnh từ public/storage vào storage/app/public
mkdir -p /app/storage/app/public/products
cp -r /app/public/storage/products/. /app/storage/app/public/products/ 2>/dev/null || true

# Tạo symlink
php artisan storage:link --force 2>/dev/null || true

php artisan migrate --force
echo "Starting server on port $PORT"
php artisan serve --host=0.0.0.0 --port=$PORT