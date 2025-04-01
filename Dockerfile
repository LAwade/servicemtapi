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


WORKDIR /app

RUN curl -s https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer
RUN alias composer='/usr/local/bin/composer'

COPY . .

RUN composer install 

## ATRIBUINDO PERMISSÕES NOS FILES
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

RUN cp .env_example .env
RUN php artisan key:generate

EXPOSE 8181
ENTRYPOINT [ "php" ]
CMD [ "php", 'artisan', 'serve', '--host=0.0.0.0', '--port=8181' ]
