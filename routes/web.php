<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/swagger', function () use ($router) {
    return redirect('/public/swagger');
});

$router->group(['prefix' => 'api'], function ($request) use ($router) {
    $router->post('users', 'UserController@store');

    $router->group(['prefix' => 'auth'], function ($request) use ($router) {
        $router->post('login/jwt', 'AuthController@loginJWT');
    });

    $router->group(['middleware' => 'auth'], function ($request) use ($router) {
        $router->group(['middleware' => 'hasRole:COLABORADOR,ADMIN'], function ($request) use ($router) {

            $router->group(['prefix' => 'authors'], function () use ($router) {
                $router->post('', 'AuthorController@store');
                $router->put('{id}', 'AuthorController@update');
            });
        });
    });
});
