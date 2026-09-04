# Mini Invoicer — Laravel 13

Simple invoicing app built with Laravel 13, PHP 8.5, SQLite + Tailwind CDN.

## Features
- **Dashboard** (`app/Http/Controllers/DashboardController.php:14`) — revenue, outstanding, drafts, overdue KPIs + recent invoices/clients
- **Clients** CRUD (`app/Models/Client.php:5`, `app/Http/Controllers/ClientController.php`) — search, VAT, invoices count
- **Invoices** CRUD (`app/Models/Invoice.php:10`, `app/Http/Controllers/InvoiceController.php`) — auto number `INV-YYYY-####`, statuses `draft|sent|paid|overdue|void`, tax% + flat discount, `subtotal`/`tax_amount`/`total` accessors, `isOverdue()`, duplicate & print
- **Items** (`app/Models/InvoiceItem.php`) — qty × unit_price = line_total, dynamic JS form in `resources/views/invoices/_form.blade.php`
- Printable invoice `resources/views/invoices/print.blade.php` (window.print → PDF)
- Validation, pagination, filters (status/q), seed data (3 clients + invoices)

## Schema
- `database/migrations/2026_09_04_000001_create_clients_table.php`
- `database/migrations/2026_09_04_000002_create_invoices_table.php`
- `database/migrations/2026_09_04_000003_create_invoice_items_table.php`

## Quick Start
```bash
cd php/invoicer-app
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan serve --port=8001
# http://127.0.0.1:8001
```

## Routes
```
GET /                         dashboard
resource clients              CRUD
resource invoices             CRUD
POST invoices/{id}/duplicate  duplicate as draft
GET invoices/{id}/print       printable view
```

## Tests
```bash
php artisan test
# 4 passed — dashboard, routes, create invoice total calc
```

## Deploy to Render (Docker + Postgres)
This repo includes `Dockerfile` and `render.yaml` (Blueprint).

**One-click via Blueprint:**
1. Push to GitHub (already done: `chiemezie-projects/laravel-invoicer`)
2. Go to https://dashboard.render.com/blueprints → **New Blueprint Instance** → Connect `laravel-invoicer` repo
3. Render reads `render.yaml` → creates **Web Service** `laravel-invoicer` (Docker, free) + **Postgres** `invoicer-db` (free, 90-day expiry)
4. Set env `APP_KEY` auto-generated, `APP_URL` = `https://<your-service>.onrender.com`
5. Deploy — build runs `composer install`, start runs `php artisan migrate --force --seed && php artisan serve --host=0.0.0.0 --port=$PORT`

**Manual (without Blueprint):**
- Dashboard → **New → Web Service** → Connect GitHub repo → Runtime **Docker** → Dockerfile `./Dockerfile`
- Add **Postgres** → New Postgres `invoicer-db` → Copy Internal DB URL vars into Web Service env as `DB_HOST/DB_PORT/DB_DATABASE/DB_USERNAME/DB_PASSWORD`, set `DB_CONNECTION=pgsql`, `APP_ENV=production`, `APP_DEBUG=false`, generate `APP_KEY` via `php artisan key:generate --show` locally and paste.
- Health check `/`, autoDeploy off.

Check logs for `Migrating...` and `Server running on 0.0.0.0:10000`.

## Stack
Laravel 13, Vite not required (Tailwind CDN + RemixIcon), SQLite (local) / Postgres (Render).

