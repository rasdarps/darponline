# cPanel Deployment Guide

This repository uses two separate workflows:

1. GitHub Actions CI validates changes (tests).
2. cPanel Git deployment updates production.

## 1) Server Layout

Set up your production app at:

- App path: `/home/techpxsg/darppos.techforgeservices.com`
- Document root: `/home/techpxsg/darppos.techforgeservices.com/public`

Do not deploy this Laravel app into `public_html` if another project already exists there.

## 2) GitHub Actions (CI only)

CI workflow is in `.github/workflows/ci.yml`.

It runs tests only and does not deploy to cPanel.

## 3) cPanel Git Deployment Script

Script file in this repo:

- `scripts/cpanel_deploy.sh`

In cPanel Git Version Control, use these deployment commands:

```bash
/bin/bash /home/techpxsg/darppos.techforgeservices.com/scripts/cpanel_deploy.sh
```

This script performs:

- composer install (production flags)
- `php artisan optimize:clear`
- `php artisan migrate --force`
- `php artisan storage:link`
- cache rebuild (`config`, `route`, `view`)

## 4) Required .env Values

At minimum:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://darppos.techforgeservices.com
DB_CONNECTION=mysql
DB_HOST=...
DB_PORT=3306
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...
QUEUE_CONNECTION=database
```

Do not run `php artisan key:generate` on every deploy. Keep your existing production `APP_KEY` stable.

## 5) Cron Jobs

Add in cPanel Cron Jobs:

```cron
* * * * * /usr/local/bin/php /home/techpxsg/darppos.techforgeservices.com/artisan schedule:run >> /dev/null 2>&1
* * * * * /usr/local/bin/php /home/techpxsg/darppos.techforgeservices.com/artisan queue:work --stop-when-empty --tries=3 >> /dev/null 2>&1
```

## 6) First Deploy Checklist

1. Confirm PHP version is 8.1 or newer.
2. Confirm required PHP extensions are enabled.
3. Set production `.env` values.
4. Run deployment from cPanel Git UI.
5. Verify app login and key flows.
6. Check logs in `storage/logs/laravel.log`.
