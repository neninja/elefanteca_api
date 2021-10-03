<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Core\Providers\{
    ICriptografiaProvider,
};

use App\Adapters\{
    LumenCryptProvider,
};

class AdapterProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            ICriptografiaProvider::class,
            function ($app) {
                return new LumenCryptProvider();
            }
        );
    }
}

