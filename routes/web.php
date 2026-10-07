<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeliveryZoneController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\BkashController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/',[HomeController::class,'index'])->name('home');
Route::get('/shop',[HomeController::class,'shop'])->name('shop');
Route::get('/product/{product:slug}',[HomeController::class,'product'])->name('product.show');

Route::get('/cart',[CartController::class,'index'])->name('cart.index');
Route::post('/cart/{product}/add',[CartController::class,'add'])->name('cart.add');
Route::patch('/cart/item/{key}',[CartController::class,'update'])->where('key','.*')->name('cart.update');
Route::delete('/cart/item/{key}',[CartController::class,'remove'])->where('key','.*')->name('cart.remove');

Route::get('/checkout',[CheckoutController::class,'show'])->name('checkout.show');
Route::post('/checkout',[CheckoutController::class,'place'])->name('checkout.place');
Route::get('/order/{orderNumber}/success',[CheckoutController::class,'success'])->name('order.success');
Route::get('/track',[CheckoutController::class,'trackForm'])->name('track.form');
Route::post('/track',[CheckoutController::class,'track'])->name('track.search');
Route::get('/payment/bkash/callback',[BkashController::class,'callback'])->name('bkash.callback');

Route::middleware('guest')->group(function(){
    Route::get('/login',[CustomerAuthController::class,'loginForm'])->name('login');
    Route::post('/login',[CustomerAuthController::class,'login'])->name('login.submit');
    Route::get('/register',[CustomerAuthController::class,'registerForm'])->name('register');
    Route::post('/register',[CustomerAuthController::class,'register'])->name('register.submit');
});

Route::middleware('auth')->group(function(){
    Route::get('/account',[AccountController::class,'dashboard'])->name('account.dashboard');
    Route::put('/account/profile',[AccountController::class,'profile'])->name('account.profile');
    Route::post('/account/addresses',[AccountController::class,'storeAddress'])->name('account.address.store');
    Route::delete('/account/addresses/{address}',[AccountController::class,'destroyAddress'])->name('account.address.destroy');

    Route::post('/logout',[CustomerAuthController::class,'logout'])->name('logout');

    Route::get('/wishlist',[WishlistController::class,'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}',[WishlistController::class,'toggle'])->name('wishlist.toggle');

    Route::post('/product/{product}/review',[ReviewController::class,'store'])->name('reviews.store');
});

Route::prefix('admin')->name('admin.')->group(function(){
    Route::get('/login',[AuthController::class,'show'])->name('login');
    Route::post('/login',[AuthController::class,'login'])->name('login.submit');

    Route::middleware('admin')->group(function(){
        Route::post('/logout',[AuthController::class,'logout'])->name('logout');
        Route::get('/',[DashboardController::class,'index'])->name('dashboard');

        Route::resource('products',AdminProductController::class)->except('show');
        Route::delete('products/{product}/images/{image}',[AdminProductController::class,'deleteImage'])->name('products.images.destroy');

        Route::get('categories',[CategoryController::class,'index'])->name('categories.index');
        Route::post('categories',[CategoryController::class,'store'])->name('categories.store');
        Route::put('categories/{category}',[CategoryController::class,'update'])->name('categories.update');
        Route::delete('categories/{category}',[CategoryController::class,'destroy'])->name('categories.destroy');

        Route::get('orders',[AdminOrderController::class,'index'])->name('orders.index');
        Route::get('orders/{order}',[AdminOrderController::class,'show'])->name('orders.show');
        Route::put('orders/{order}',[AdminOrderController::class,'update'])->name('orders.update');
        Route::post('orders/{order}/courier',[AdminOrderController::class,'courier'])->name('orders.courier');
        Route::get('orders/{order}/invoice.pdf',[InvoiceController::class,'pdf'])->name('orders.invoice');

        Route::get('reviews',[AdminReviewController::class,'index'])->name('reviews.index');
        Route::put('reviews/{review}/approve',[AdminReviewController::class,'approve'])->name('reviews.approve');
        Route::delete('reviews/{review}',[AdminReviewController::class,'destroy'])->name('reviews.destroy');

        Route::middleware('admin:owner,manager')->group(function(){
            Route::get('hero-slides',[HeroSlideController::class,'index'])->name('hero-slides.index');
            Route::post('hero-slides',[HeroSlideController::class,'store'])->name('hero-slides.store');
            Route::put('hero-slides/{heroSlide}',[HeroSlideController::class,'update'])->name('hero-slides.update');
            Route::delete('hero-slides/{heroSlide}',[HeroSlideController::class,'destroy'])->name('hero-slides.destroy');

            Route::get('delivery-zones',[DeliveryZoneController::class,'index'])->name('delivery-zones.index');
            Route::post('delivery-zones',[DeliveryZoneController::class,'store'])->name('delivery-zones.store');
            Route::put('delivery-zones/{deliveryZone}',[DeliveryZoneController::class,'update'])->name('delivery-zones.update');
            Route::delete('delivery-zones/{deliveryZone}',[DeliveryZoneController::class,'destroy'])->name('delivery-zones.destroy');

            Route::get('reports/sales',[ReportController::class,'sales'])->name('reports.sales');
        });

        Route::middleware('admin:owner')->group(function(){
            Route::get('settings',[SettingsController::class,'edit'])->name('settings.edit');
            Route::put('settings',[SettingsController::class,'update'])->name('settings.update');

            Route::get('admins',[AdminUserController::class,'index'])->name('admins.index');
            Route::post('admins',[AdminUserController::class,'store'])->name('admins.store');
            Route::put('admins/{user}',[AdminUserController::class,'update'])->name('admins.update');
            Route::delete('admins/{user}',[AdminUserController::class,'destroy'])->name('admins.destroy');
        });
    });
});
