FROM php:8.2-cli

WORKDIR /app

COPY . .

RUN apt-get update && apt-get install -y \
    unzip git curl libzip-dev libpng-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip gd xml

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN chmod -R 777 storage bootstrap/cache

# Xóa .env cũ để Railway Variables có thể hoạt động
RUN rm -f .env bootstrap/cache/config.php bootstrap/cache/routes*.php

CMD php -r "
\$vars = [
    'APP_NAME' => getenv('APP_NAME') ?: 'HV Pet Shop',
    'APP_ENV' => getenv('APP_ENV') ?: 'production',
    'APP_KEY' => getenv('APP_KEY') ?: '',
    'APP_DEBUG' => getenv('APP_DEBUG') ?: 'false',
    'APP_URL' => getenv('APP_URL') ?: 'http://localhost',
    'DB_CONNECTION' => getenv('DB_CONNECTION') ?: 'mysql',
    'DB_HOST' => getenv('DB_HOST') ?: '127.0.0.1',
    'DB_PORT' => getenv('DB_PORT') ?: '3306',
    'DB_DATABASE' => getenv('DB_DATABASE') ?: '',
    'DB_USERNAME'