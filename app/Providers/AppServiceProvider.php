<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Doctrine\ORM\EntityManagerInterface;
use App\Repositories\Doctrine\EntityManagerFactory;

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
use App\Repositories\Doctrine\{
    UsuariosRepository,
};

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {


        $this->app->singleton(
            EntityManagerInterface::class,
            function ($app) {
                return (new EntityManagerFactory())->get();
            }
        );

        $this->app->bind(
            IUsuariosRepository::class,
            function ($app) {
                return new UsuariosRepository(
                    $app->make(EntityManagerInterface::class),
                );
            }
        );

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
