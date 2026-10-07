<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('store:about', function () {
    $this->info('Aesthetic Store ecommerce application');
})->purpose('Show store application info');
