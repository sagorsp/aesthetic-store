# cPanel-এ Aesthetic Store Deploy — বাংলা ধাপে ধাপে

## Recommended structure

Laravel core `public_html`-এর বাইরে রাখো:

```text
/home/CPANEL_USER/aesthetic-store
```

আর `aestheticstore.bd` domain-এর document root দাও:

```text
/home/CPANEL_USER/aesthetic-store/public
```

এটাই সবচেয়ে নিরাপদ setup।

## 1) Database বানাও

cPanel → **MySQL Databases**:

- নতুন database create করো
- database user create করো
- user-কে database-এ **ALL PRIVILEGES** দাও
- cPanel prefix সহ exact DB name/user note করে রাখো

## 2) Project upload

এই ZIP cPanel File Manager দিয়ে home directory-তে upload করে extract করো। Folder name রাখো:

```text
aesthetic-store
```

## 3) Domain document root

cPanel → **Domains** → `aestheticstore.bd` → Document Root:

```text
/home/CPANEL_USER/aesthetic-store/public
```

## 4) Composer install

cPanel Terminal খুলে:

```bash
cd ~/aesthetic-store
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
```

Composer command না থাকলে hosting provider-এর Composer feature ব্যবহার করো, অথবা local machine-এ Composer install করে `vendor` folder upload করো।

## 5) `.env` edit

কমপক্ষে এগুলো ঠিক করো:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://aestheticstore.bd

DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=YOUR_CPANEL_DB
DB_USERNAME=YOUR_CPANEL_DB_USER
DB_PASSWORD=YOUR_DB_PASSWORD

STORE_PHONE=01550720798
STORE_EMAIL=support@aestheticstore.bd
ADMIN_EMAIL=shagurhd@gmail.com
ADMIN_PASSWORD=FIRST_TIME_STRONG_PASSWORD
```

## 6) Database migrate + admin create

```bash
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
```

তারপর admin login:

```text
https://aestheticstore.bd/admin/login
```

প্রথমবার login হওয়ার পরে `.env` থেকে `ADMIN_PASSWORD` value remove/change করে দাও। Database-এ password hash হিসেবে save হবে।

## 7) Permission

```bash
chmod -R 775 storage bootstrap/cache
```

`777` avoid করো।

## 8) Cache/optimize

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 9) bKash sandbox

`.env`:

```dotenv
BKASH_ENABLED=true
BKASH_SANDBOX=true
BKASH_APP_KEY=YOUR_KEY
BKASH_APP_SECRET=YOUR_SECRET
BKASH_USERNAME=YOUR_USERNAME
BKASH_PASSWORD=YOUR_PASSWORD
BKASH_CALLBACK_URL=https://aestheticstore.bd/payment/bkash/callback
```

Sandbox test successful হলে live credentials বসিয়ে:

```dotenv
BKASH_SANDBOX=false
```

তারপর:

```bash
php artisan config:clear
php artisan config:cache
```

API secret কখনো Blade/JavaScript-এ রাখবে না। `.env`-এই থাকবে।

## 10) Launch test

Live করার আগে এই flow test করো:

1. Admin login
2. Category create
3. Product create + image upload
4. Storefront product open
5. Cart add/update/remove
6. COD checkout
7. Admin order receive
8. Order status change
9. Customer order tracking
10. COD cancel করলে stock ফেরত আসে কি না
11. bKash sandbox checkout
12. Successful bKash callback-এর পরে payment status `paid` এবং stock decrease হয় কি না
13. SSL/HTTPS active

## যদি document root `/public` করা না যায়

Laravel project রাখো:

```text
/home/CPANEL_USER/aesthetic-store
```

Laravel-এর `public` folder-এর content copy করো:

```text
/home/CPANEL_USER/public_html
```

তারপর `public_html/index.php`-এর path change করো:

```php
require __DIR__.'/../aesthetic-store/vendor/autoload.php';
(require_once __DIR__.'/../aesthetic-store/bootstrap/app.php')->handleRequest(Request::capture());
```

Project-এর `deploy/cpanel-public-index.php.example` ready example হিসেবে দেওয়া আছে।
