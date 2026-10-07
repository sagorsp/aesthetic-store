<?php
namespace App\Providers;

use App\Models\Category;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        try {
            if (Schema::hasTable('settings')) {
                View::share('storeSettings', Setting::pluck('value', 'key')->all());
            }
            if (Schema::hasTable('categories')) {
                View::share('navCategories', Category::where('is_active', true)->orderBy('sort_order')->take(8)->get());
            }
        } catch (Throwable $e) {
            View::share('storeSettings', []);
            View::share('navCategories', collect());
        }
    }
}
