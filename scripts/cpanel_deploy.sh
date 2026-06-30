#!/bin/bash
set -euo pipefail

APP_PATH="/home/techpxsg/darppos.techforgeservices.com"
cd "$APP_PATH"

echo "[deploy] Running in $APP_PATH"

if [ -x /opt/cpanel/composer/bin/composer ]; then
  COMPOSER_BIN="/opt/cpanel/composer/bin/composer"
elif command -v composer >/dev/null 2>&1; then
  COMPOSER_BIN="composer"
else
  echo "[deploy] Composer not found." >&2
  exit 1
fi

$COMPOSER_BIN install --no-dev --optimize-autoloader --no-interaction

php artisan optimize:clear
php artisan migrate --force
php artisan storage:link || true
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "[deploy] Deployment steps completed successfully."
