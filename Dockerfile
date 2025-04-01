FROM php:8.2-fpm

RUN apt-get update -y \
    && apt-get install -y \
    postgresql-client \
    openssl \
    zip \
    unzip \
    git \
    curl \
    libpq-dev \
    libonig-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring \
    && rm -rf /var/lib/apt/lists/* 

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app
COPY . .
RUN composer install --no-dev --prefer-dist --no-progress --no-suggest

RUN cp .env_example .env

RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

RUN php artisan key:generate

RUN php artisan migrate
RUN php artisan db:seed

EXPOSE 8181
ENTRYPOINT ["php"]

CMD [ "php", 'artisan', 'serve', '--host=0.0.0.0', '--port=8181' ]
