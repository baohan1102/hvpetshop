#!/bin/bash
set -e

echo "=== DEBUG ENV VARS ==="
echo "DB_HOST=${DB_HOST}"
echo "DB_DATABASE=${DB_DATABASE}"
echo "DB_USERNAME=${DB_USERNAME}"
echo "APP_KEY=${APP_KEY}"
echo "======================"

# Tạo .env từ environment variables
cat > /app/.env << EOF
APP_NAME="HV Pet Shop"
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY}
APP_DEBUG=true
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

echo "=== .env content ==="
cat /app/.env
echo "===================="

php artisan migrate --force
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}