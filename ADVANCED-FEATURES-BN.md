# Aesthetic Store — Advanced Features Upgrade

এই build-এ implement করা হয়েছে:

- Multiple product images / gallery
- Product variants (name, SKU, color, size, own price, own stock)
- Customer wishlist
- Customer reviews + admin approval
- District-based delivery charges
- Multiple admin-managed hero sliders
- Generic SMS gateway adapter
- Generic courier API adapter + shipment/tracking storage
- bKash sandbox/live Tokenized Checkout flow + payment logs
- PDF invoice download
- Sales reports (date range, revenue, daily sales, top products)
- Role-based admin users: owner / manager / staff

## Existing local project upgrade

Project folder-এ updated files replace করার পর:

```bash
composer install
php artisan migrate
php artisan db:seed
php artisan storage:link
php artisan optimize:clear
php artisan serve
```

PDF invoice-এর জন্য `composer.json`-এ `barryvdh/laravel-dompdf` যোগ করা আছে। `composer install` এটা install করবে। যদি পুরনো project-এ শুধু patch copy করো, চালাও:

```bash
composer require barryvdh/laravel-dompdf:^3.1
```

## Admin URLs

- `/admin/products` — gallery + variants
- `/admin/reviews` — review moderation
- `/admin/hero-slides` — multiple hero banners
- `/admin/delivery-zones` — district delivery charge
- `/admin/reports/sales` — sales report
- `/admin/admins` — owner-only admin/role management
- Order details → PDF Invoice / Courier booking

## SMS setup (.env)

```env
SMS_ENABLED=true
SMS_API_URL=https://YOUR-SMS-PROVIDER-ENDPOINT
SMS_API_KEY=YOUR_KEY
SMS_SENDER_ID=AestheticStore
```

`app/Services/SmsService.php` একটি generic adapter। Provider যদি অন্য field names চায়, শুধু ওই service-এর POST payload map করবে। Credentials কখনো Blade/JavaScript-এ দেবে না।

## Courier setup (.env)

```env
COURIER_ENABLED=true
COURIER_PROVIDER=steadfast
COURIER_BASE_URL=https://YOUR-COURIER-API
COURIER_API_KEY=YOUR_KEY
```

`app/Services/CourierService.php` generic adapter। Pathao / Steadfast / RedX যে provider নেবে, তাদের exact endpoint এবং payload অনুযায়ী এই একটিমাত্র service map করবে। Orders page থেকে shipment create করা যাবে।

## bKash live setup

Sandbox test-এর সময়:

```env
BKASH_ENABLED=true
BKASH_SANDBOX=true
BKASH_APP_KEY=...
BKASH_APP_SECRET=...
BKASH_USERNAME=...
BKASH_PASSWORD=...
BKASH_CALLBACK_URL=http://127.0.0.1:8000/payment/bkash/callback
```

Live-এ:

```env
BKASH_SANDBOX=false
BKASH_CALLBACK_URL=https://aestheticstore.bd/payment/bkash/callback
```

তারপর:

```bash
php artisan config:clear
php artisan config:cache
```

## Role permissions

- `owner`: সব access, settings + admin users
- `manager`: products, categories, orders, reviews, hero, delivery, reports
- `staff`: products, categories, orders, reviews

## Database tables added

`product_images`, `product_variants`, `wishlists`, `reviews`, `delivery_zones`, `hero_slides`, `payments`, `shipment_events` এবং orders/order_items/users-এ প্রয়োজনীয় extra columns।
