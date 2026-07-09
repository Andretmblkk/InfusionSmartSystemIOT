#!/usr/bin/env sh
set -e

if [ -z "${APP_KEY:-}" ]; then
    export APP_KEY="$(php -r 'echo "base64:".base64_encode(random_bytes(32));')"
fi

if [ "${DB_CONNECTION:-}" = "mysql" ]; then
    echo "Waiting for MySQL at ${DB_HOST:-mysql}:${DB_PORT:-3306}..."

    until mysqladmin ping -h"${DB_HOST:-mysql}" -P"${DB_PORT:-3306}" -u"${DB_USERNAME:-monitoring}" --password="${DB_PASSWORD:-monitoring}" --protocol=tcp --ssl=0 --silent; do
        sleep 2
    done

    echo "MySQL is ready."
fi

php artisan config:clear --no-interaction
php artisan route:clear --no-interaction
php artisan migrate --force --no-interaction
php artisan db:seed --force --no-interaction

exec "$@"
