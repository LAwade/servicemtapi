#!/bin/sh
set -e

## GERENDO ARQUIVO ENV
if [ ! -f ".env" ]; then
    echo "Gerando arquivo ENV"
    cp .env_example .env
fi

## TESTANDO BANCO DE DADOS
while ! pg_isready -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USERNAME" >/dev/null 2>&1; do
    sleep 2
done
echo "PostgreSQL [ OK ]"

## KEY DO PROJETO
if [ ! -f "storage/oauth-private.key" ]; then
    php artisan key:generate
fi

## RUNNING MIGRATIONS
if [ $(php artisan migrate:status | grep -c 'Yes') -eq 0 ]; then
    php artisan migrate --force
else
    echo "Migration [ OK ]"
fi

## RUNNING SEEDS
php artisan db:seed