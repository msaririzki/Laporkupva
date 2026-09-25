#!/bin/sh
set -eu

database_path="${DB_DATABASE:-/var/lib/laporkupva/database.sqlite}"

mkdir -p \
    "$(dirname "$database_path")" \
    storage/app/private \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs

touch "$database_path"
chown -R www-data:www-data "$(dirname "$database_path")" storage bootstrap/cache

php artisan migrate --force --no-interaction
php artisan storage:link --force --no-interaction
php artisan optimize

exec "$@"
