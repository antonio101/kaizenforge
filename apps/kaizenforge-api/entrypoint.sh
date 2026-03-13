#!/bin/sh
set -e

cd /var/www/html

umask 0002

mkdir -p var/cache var/log || true

chown -R www-data:www-data var 2>/dev/null || true

find var -type d -exec chmod 775 {} \; 2>/dev/null || true
find var -type f -exec chmod 664 {} \; 2>/dev/null || true

if [ ! -d vendor ]; then
  echo "Warning: vendor/ not found. Run: make deps"
  exec "$@"
fi

if [ "${APP_ENV}" = "dev" ] && [ -f bin/console ]; then
  echo "Running database migrations for development environment..."
  php bin/console doctrine:migrations:migrate --no-interaction
fi

exec "$@"