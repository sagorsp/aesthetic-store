# Aesthetic Store — Laravel + Blade + Tailwind + DaisyUI

A lightweight ecommerce codebase for **aestheticstore.bd** using Laravel, Blade templates, Tailwind CSS and DaisyUI. It is designed for normal cPanel shared hosting and deliberately avoids a heavy SPA stack.

## What is included

- Storefront home page, shop/search/filter/sort, product detail pages
- Session cart with stock limits
- Checkout for Bangladesh with Dhaka/outside-Dhaka delivery charges
- Cash on Delivery flow
- bKash Tokenized Checkout service layer + callback/execute flow
- Order number generation and customer order tracking
- Stock commit / release logic
- Admin login
- Admin dashboard
- Product CRUD with image upload
- Category management
- Order management and status/payment updates
- Editable homepage/support settings
- MySQL migrations + seed data
- Blade + Tailwind + DaisyUI UI
- No npm/Vite build is required: Tailwind Browser + DaisyUI CDN are used for the lightweight deployment

## Requirements

- PHP 8.2+
- Composer 2
- MySQL / MariaDB
- Required PHP extensions normally enabled by Laravel: Ctype, cURL, DOM, Fileinfo, Filter, Hash, Mbstring, OpenSSL, PCRE, PDO, Session, Tokenizer, XML
- HTTPS / SSL strongly recommended

## Local install

```bash
composer install
cp .env.example .env
php artisan key:generate
# edit DB_* values in .env
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Admin URL: `/admin/login`

The seeded admin email comes from `ADMIN_EMAIL` in `.env` and the first password comes from `ADMIN_PASSWORD`. **Change the password value before seeding production.**

## cPanel deployment — recommended method

The safest setup is to keep the Laravel application outside `public_html` and point your domain document root to Laravel's `public` directory.

Example:

```text
/home/CPANEL_USER/aesthetic-store/        <- Laravel project
/home/CPANEL_USER/aesthetic-store/public/ <- document root for aestheticstore.bd
```

### 1. Create database

In cPanel → **MySQL Databases**:

1. Create a database.
2. Create a database user.
3. Add the user to the database with **ALL PRIVILEGES**.
4. Remember that cPanel usually prefixes DB names/users with your cPanel username.

### 2. Upload project

Upload the ZIP to your home directory and extract it as:

```text
/home/CPANEL_USER/aesthetic-store
```

Do not expose `.env`, `app`, `vendor`, `storage`, or `database` directly under the public web root.

### 3. Set the domain document root

In cPanel → **Domains**, set `aestheticstore.bd` document root to:

```text
/home/CPANEL_USER/aesthetic-store/public
```

If your hosting provider does not allow this, see the fallback deployment section below.

### 4. Install PHP dependencies

Open **cPanel Terminal**:

```bash
cd ~/aesthetic-store
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
```

If your cPanel has no Composer command, use the hosting provider's Composer interface or run `composer install --no-dev --optimize-autoloader` locally with the same PHP-compatible version and upload the `vendor` directory.

### 5. Edit `.env`

Set at least:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://aestheticstore.bd

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=CPANELUSER_database
DB_USERNAME=CPANELUSER_dbuser
DB_PASSWORD=YOUR_STRONG_DB_PASSWORD

STORE_PHONE=01550720798
STORE_EMAIL=support@aestheticstore.bd
ADMIN_EMAIL=shagurhd@gmail.com
ADMIN_PASSWORD=USE_A_NEW_STRONG_PASSWORD_BEFORE_FIRST_SEED
```

Do not send production bKash secrets in chat or commit them to source control.

### 6. Run migrations and seed

```bash
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
```

Then change/remove `ADMIN_PASSWORD` from `.env` after the admin user has been created. Laravel's user record stores a hash, not the plain password.

### 7. Permissions

```bash
chmod -R 775 storage bootstrap/cache
```

Exact ownership rules differ by host. Do not use `777` unless your host explicitly requires it.

### 8. Optimize Laravel

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 9. SSL

Enable AutoSSL / Let's Encrypt from cPanel and force HTTPS at the domain level.

## cPanel fallback when document root cannot point to `/public`

Recommended layout:

```text
/home/CPANEL_USER/aesthetic-store/  <- Laravel core files, NOT public
/home/CPANEL_USER/public_html/      <- only contents of Laravel public/
```

Copy everything from `aesthetic-store/public/` into `public_html/`, then edit `public_html/index.php` and change:

```php
require __DIR__.'/../vendor/autoload.php';
(require_once __DIR__.'/../bootstrap/app.php')
```

to:

```php
require __DIR__.'/../aesthetic-store/vendor/autoload.php';
(require_once __DIR__.'/../aesthetic-store/bootstrap/app.php')
```

The `.htaccess` from this project's `public/` folder must also be in `public_html/`.

For `storage:link`, some shared hosts do not allow symlinks. If the command fails, ask the host to enable symlink support. Product images rely on `public/storage` pointing to `storage/app/public`.

## bKash setup

The code reads all bKash values from `.env`:

```dotenv
BKASH_ENABLED=true
BKASH_SANDBOX=true
BKASH_APP_KEY=
BKASH_APP_SECRET=
BKASH_USERNAME=
BKASH_PASSWORD=
BKASH_CALLBACK_URL=https://aestheticstore.bd/payment/bkash/callback
```

Start with sandbox. After bKash approves live access, use live credentials and set:

```dotenv
BKASH_SANDBOX=false
```

If bKash changes an endpoint/version, update `BKASH_BASE_URL_SANDBOX` and `BKASH_BASE_URL_LIVE` without changing the application logic.

### Security note

- Never put bKash secrets in Blade templates or JavaScript.
- Keep `.env` outside the public web root.
- Keep `APP_DEBUG=false` in production.
- Use HTTPS only for checkout and callbacks.

## Store configuration

Delivery defaults:

```dotenv
DHAKA_DELIVERY_CHARGE=80
OUTSIDE_DHAKA_DELIVERY_CHARGE=130
```

The homepage hero/support details can be edited from:

```text
/admin/settings
```

## Production checklist

1. Add real products and product images.
2. Test COD checkout end to end.
3. Test order tracking.
4. Configure bKash sandbox and complete a sandbox payment.
5. Confirm stock decreases after COD order and after successful bKash payment.
6. Test cancel/return stock restoration from admin.
7. Enable HTTPS.
8. Back up database and files before live launch.
9. Replace the initial admin password.
10. Set `APP_DEBUG=false` and cache config/routes/views.

## Notes

This package is intentionally lightweight. Tailwind and DaisyUI are delivered from CDN, so there is no Node/npm build step. If you later want fully self-hosted CSS assets, add Vite/Tailwind/DaisyUI compilation and replace the CDN tags in the two layouts.
