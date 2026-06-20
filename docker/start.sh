#!/usr/bin/env sh
set -eu

PORT="${PORT:-10000}"

if [ "${APP_ENV:-production}" = "production" ]; then
    if [ -z "${APP_KEY:-}" ]; then
        echo "APP_KEY is required in production." >&2
        exit 1
    fi

    if [ -z "${DB_URL:-}" ] && [ -z "${DB_HOST:-}" ]; then
        echo "Set DB_URL or the individual PostgreSQL DB_* variables." >&2
        exit 1
    fi
fi

mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/testing \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

php artisan optimize:clear
php artisan migrate --force

if [ "${SEED_INITIAL_DATA:-false}" = "true" ]; then
    php artisan db:seed --force
fi

if [ -n "${ADMIN_EMAIL:-}" ] || [ -n "${ADMIN_PASSWORD:-}" ]; then
    if [ -z "${ADMIN_EMAIL:-}" ] || [ -z "${ADMIN_PASSWORD:-}" ]; then
        echo "Both ADMIN_EMAIL and ADMIN_PASSWORD must be set together." >&2
        exit 1
    fi

    php artisan admin:create --no-interaction
fi

php artisan config:cache
php artisan view:cache

exec php artisan serve --host=0.0.0.0 --port="${PORT}"
