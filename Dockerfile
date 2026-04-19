FROM php:8.2-cli

WORKDIR /app
COPY . .

RUN apt-get update && apt-get install -y unzip git curl libzip-dev
RUN docker-php-ext-install pdo pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php
RUN php composer.phar install

RUN chmod -R 777 storage bootstrap/cache

EXPOSE 8080

CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8080} -t public"]