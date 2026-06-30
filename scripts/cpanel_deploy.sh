#!/bin/bash
set -euo pipefail

APP_PATH="/home/techpxsg/darppos.techforgeservices.com"
cd "$APP_PATH"

echo "[deploy] Running in $APP_PATH"

if [ -x /opt/cpanel/composer/bin/composer ]; then
  COMPOSER_BIN="/opt/cpanel/composer/bin/composer"
elif command -v composer >/dev/null 2>&1; then
  COMPOSER_BIN="composer"
elif [ -x "$HOME/bin/composer" ]; then
  COMPOSER_BIN="$HOME/bin/composer"
elif [ -x "$HOME/.local/bin/composer" ]; then
  COMPOSER_BIN="$HOME/.local/bin/composer"
elif [ -x /usr/local/bin/composer ]; then
  COMPOSER_BIN="/usr/local/bin/composer"
elif [ -x /usr/bin/composer ]; then
  COMPOSER_BIN="/usr/bin/composer"
elif [ -f "$APP_PATH/composer.phar" ]; then
  COMPOSER_BIN="php $APP_PATH/composer.phar"
else
  echo "[deploy] Composer not found in standard paths; downloading composer.phar..."
  php -r "copy('https://getcomposer.org/installer', '/tmp/composer-setup.php');" \
    || { echo "[deploy] Failed to download Composer installer." >&2; exit 1; }
  EXPECTED_SIG="$(php -r "echo hash_file('sha384', '/tmp/composer-setup.php');")"
  ACTUAL_SIG="$(curl -sSL https://composer.github.io/installer.sig)"
  if [ "$EXPECTED_SIG" != "$ACTUAL_SIG" ]; then
    echo "[deploy] Composer installer signature mismatch; aborting." >&2
    rm -f /tmp/composer-setup.php
    exit 1
  fi
  php /tmp/composer-setup.php --install-dir="$APP_PATH" --filename=composer.phar \
    || { echo "[deploy] Composer installer failed." >&2; rm -f /tmp/composer-setup.php; exit 1; }
  rm -f /tmp/composer-setup.php
  [ -f "$APP_PATH/composer.phar" ] \
    || { echo "[deploy] composer.phar not found after install." >&2; exit 1; }
  COMPOSER_BIN="php $APP_PATH/composer.phar"
fi

$COMPOSER_BIN install --no-dev --optimize-autoloader --no-interaction

if [ ! -f "$APP_PATH/.env" ]; then
  echo "[deploy] Missing $APP_PATH/.env. Create production .env before running deploy." >&2
  exit 1
fi

if grep -Eq '^DB_DATABASE=forge\s*$|^DB_USERNAME=forge\s*$' "$APP_PATH/.env"; then
  echo "[deploy] .env still has default Forge DB values. Set DB_DATABASE/DB_USERNAME/DB_PASSWORD for production." >&2
  exit 1
fi

php artisan optimize:clear
php artisan migrate --force
php artisan storage:link || true
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "[deploy] Deployment steps completed successfully."
