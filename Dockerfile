FROM php:8.2-cli

WORKDIR /app
COPY . .

RUN apt-get update && apt-get install -y unzip git curl
RUN curl -sS https://getcomposer.org/installer | php
RUN php composer.phar install

RUN chmod -R 777 storage bootstrap/cache

CMD php -S 0.0.0.0:$PORT -t public