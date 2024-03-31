FROM php:8.0-cli

RUN docker-php-ext-install pdo_mysql

RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/* \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader

CMD ["php", "-S", "0.0.0.0:8081", "-t", "public"]
