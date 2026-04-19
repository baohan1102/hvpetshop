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
VNPAY_TMN_CODE=${VNPAY_TMN_CODE:-DEJE6R68}
VNPAY_HASH_SECRET=${VNPAY_HASH_SECRET:-UGWZ7VQLQ44X9BGKHRI9ZZ0PVRPJLLBA}
VNPAY_URL=${VNPAY_URL:-https://sandbox.vnpayment.vn/paymentv2/vpcpay.html}
VNPAY_RETURN_URL=${VNPAY_RETURN_URL:-https://hvpetshop-production.up.railway.app/vnpay/return}
EOF

# Xóa symlink cũ nếu có
rm -rf /app/public/storage

# Copy ảnh trực tiếp vào public/storage (không dùng symlink)
mkdir -p /app/public/storage/products
cp -r /app/storage/app/public/products/. /app/public/storage/products/ 2>/dev/null || true

echo "Images copied:"
ls /app/public/storage/products/ 2>/dev/null || echo "No images found"

php artisan migrate --force
echo "Starting server on port $PORT"
php artisan serve --host=0.0.0.0 --port=$PORT