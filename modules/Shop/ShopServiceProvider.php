<?php

namespace Modules\Shop;

use Illuminate\Support\ServiceProvider;

class ShopServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/Routes/shop.php');
        $this->loadViewsFrom(__DIR__ . '/Views', 'shop');
    }
} 