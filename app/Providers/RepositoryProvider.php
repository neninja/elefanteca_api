<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Doctrine\ORM\EntityManagerInterface;
use App\Repositories\Doctrine\EntityManagerFactory;

use Core\Repositories\{
    IUsuariosRepository,
    IAutoresRepository,
    ILivrosRepository,
};

use App\Repositories\Doctrine\{
    UsuariosRepository,
    AutoresRepository,
    LivrosRepository,
};

class RepositoryProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(
            EntityManagerInterface::class,
            function ($app) {
                return (new EntityManagerFactory())->get();
            }
        );

        $this->app->bind(
            IAutoresRepository::class,
            function ($app) {
                return new AutoresRepository(
                    $app->make(EntityManagerInterface::class),
                );
            }
        );

        $this->app->bind(
            ILivrosRepository::class,
            function ($app) {
                return new LivrosRepository(
                    $app->make(EntityManagerInterface::class),
                );
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
    }
}

