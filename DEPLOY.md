# Live deploy — rtb.rebelcommunicationchat.com

## Current hosting path

Document root should be:

```text
/home/sites/26b/5/5cbaefac17/public_html/rtb/public
```

So project files must exist like this:

```text
/home/sites/.../public_html/rtb/
  app/
  bootstrap/
  config/
  database/
  public/          <--- domain points HERE
    index.php
    .htaccess
    css/
    images/
  routes/
  storage/
  vendor/
  .env
  artisan
  composer.json
```

If `public/index.php` is missing at that exact path, Apache shows **403/404**.

## Fix checklist (do in order)

1. Upload / clone full repo into:
   `/home/sites/26b/5/5cbaefac17/public_html/rtb`
2. Confirm these exist on server:
   - `.../rtb/public/index.php`
   - `.../rtb/public/.htaccess`
   - `.../rtb/vendor/autoload.php`
   - `.../rtb/.env`
3. Domain document root must be exactly:
   `/home/sites/26b/5/5cbaefac17/public_html/rtb/public`
4. Permissions:
   ```bash
   chmod -R 755 /home/sites/26b/5/5cbaefac17/public_html/rtb
   chmod -R 775 /home/sites/26b/5/5cbaefac17/public_html/rtb/storage
   chmod -R 775 /home/sites/26b/5/5cbaefac17/public_html/rtb/bootstrap/cache
   ```
5. Install PHP deps (on server SSH, or upload `vendor` from local):
   ```bash
   cd /home/sites/26b/5/5cbaefac17/public_html/rtb
   composer install --no-dev --optimize-autoloader
   ```
6. Create DB + migrate:
   ```bash
   touch database/database.sqlite
   php artisan key:generate --force
   php artisan migrate --force
   php artisan config:clear
   php artisan cache:clear
   ```

## Recommended `.env` for this host

```env
APP_NAME="Rebel Communication"
APP_ENV=production
APP_KEY=base64:PUT_YOUR_KEY_HERE
APP_DEBUG=false
APP_URL=https://rtb.rebelcommunicationchat.com

DB_CONNECTION=sqlite
DB_DATABASE=/home/sites/26b/5/5cbaefac17/public_html/rtb/database/database.sqlite

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync

RINGBA_RTB_ENDPOINT=https://rtb.ringba.com/v1/production/ec23a7fce72944aba7f568c96aa931b2.json
RINGBA_RTB_TIMEOUT=15
RINGBA_TRAFFIC_SOURCE=rebelcommunication
```

Important:

- Use absolute `DB_DATABASE` path on Linux hosting
- Prefer `SESSION_DRIVER=file` and `CACHE_STORE=file` on shared hosting
- Do **not** point domain to `/rtb` — point to `/rtb/public`

## Ringba `No capacity (Code: 1003)`

App is sending correctly. Example successful ping:

```json
{
  "bidId": "RTB...",
  "bidAmount": 0,
  "rejectReason": "No capacity (Code: 1003)"
}
```

This means Ringba received the lead, but no buyer/target is available (capacity, hours, filters, or disabled target). Fix that in Ringba campaign settings.

## Quick test after deploy

Open:

- https://rtb.rebelcommunicationchat.com/
- https://rtb.rebelcommunicationchat.com/css/dashboard.css

If CSS opens, document root is correct.
