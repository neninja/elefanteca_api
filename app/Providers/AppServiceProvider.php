<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Core\Services\{
    Usuario\CadastroUsuarioService,
};

use Core\Providers\{
    ICriptografiaProvider,
};
use App\Adapters\{
    LumenCryptProvider,
};

use Core\Repositories\{
    IUsuariosRepository,
};

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            ICriptografiaProvider::class,
            function ($app) {
                return new LumenCryptProvider();
            }
        );

        $this->app->bind(
            CadastroUsuarioService::class,
            function ($app) {
                return new CadastroUsuarioService(
                    $app->make(IUsuariosRepository::class),
                    $app->make(ICriptografiaProvider::class)
                );
            }
        );
    }
}
