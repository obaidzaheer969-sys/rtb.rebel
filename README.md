# Rebel Communication — Agent Dashboard

Premium Laravel agent dashboard for customer call submissions with live Ringba RTB bidding.

## Features

- Rebel Communication branded agent UI
- Customer phone, name, ZIP, and traffic source capture
- Backend integration with Ringba RTB:
  `https://rtb.ringba.com/v1/production/ec23a7fce72944aba7f568c96aa931b2.json`
- Live bid result panel
- Ping history stored in SQLite

## Requirements

- PHP 8.3+
- Composer
- SQLite (enabled by default)

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

Open [http://127.0.0.1:8000](http://127.0.0.1:8000).

## Environment

| Variable | Description |
|---|---|
| `RINGBA_RTB_ENDPOINT` | Ringba RTB JSON endpoint |
| `RINGBA_RTB_TIMEOUT` | HTTP timeout in seconds |
| `RINGBA_TRAFFIC_SOURCE` | Default traffic source value |

## API flow

Form submit → Laravel `POST /submit` → Ringba RTB bid request (`CID`, `zipcode`, `exposeCallerId`, optional name/source) → response saved and returned to the UI.
