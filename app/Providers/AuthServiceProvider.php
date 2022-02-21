<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            if (!$request->hasHeader('Authorization')) {
                return null;
            }

            $authorizationHeader = $request->header('Authorization');
            $token = str_replace('Bearer ', '', $authorizationHeader);
            $dadosAutenticacao = JWT::decode($token, new Key(env('JWT_KEY'), 'HS256'));

            $repo = app()->make(\App\Repositories\Doctrine\UsuariosRepository::class);

            $usuario = $repo->findByEmail($dadosAutenticacao->email);

            $user           = new \App\Models\User();
            $user->id       = $usuario->getId();
            $user->name     = $usuario->nome;
            $user->email    = $usuario->email;
            $user->role     = $usuario->papel->get();

            return $user;
        });

        Gate::define('papel', function (User $_, string $papel) {
            /*
             * Usar \Auth::user() ao invés do parametro User para
             * facilitar nos testes ou outras formas de garantir
             * o usuário logado
             */
            return \Auth::user()->role === $papel;
        });
    }
}
