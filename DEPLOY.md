# Live deploy — rtb.rebelcommunicationchat.com

## 403 Forbidden fix

Apache returns **403 Forbidden** when the domain root is not pointing at Laravel's `public` folder, or when app files are missing.

### Option A (best): point document root to `public`

In cPanel / hosting:

1. Domain: `rtb.rebelcommunicationchat.com`
2. Document Root → `.../rtb.rebel/public` (must end with `/public`)
3. Save and wait a minute

### Option B: keep root as project folder

This repo includes root `.htaccess` + `index.php` that forward into `public/`.

Requirements:

- Apache `mod_rewrite` enabled
- `AllowOverride All` for the site

## Server setup

```bash
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
```

Edit `.env`:

```env
APP_NAME="Rebel Communication"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://rtb.rebelcommunicationchat.com

DB_CONNECTION=sqlite

RINGBA_RTB_ENDPOINT=https://rtb.ringba.com/v1/production/ec23a7fce72944aba7f568c96aa931b2.json
RINGBA_TRAFFIC_SOURCE=rebelcommunication
```

Then:

```bash
touch database/database.sqlite
php artisan migrate --force
chmod -R 775 storage bootstrap/cache
```

## Upload checklist

- Upload full project (including `vendor` if server has no Composer)
- Ensure `public/images/rebel-crest.png` exists
- Ensure `.env` exists on server (never commit secrets carelessly)
- Clear cache: `php artisan config:clear && php artisan cache:clear`

## Test

Open: https://rtb.rebelcommunicationchat.com
