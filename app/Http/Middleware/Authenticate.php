<?php

namespace App\Http\Middleware;

use App\Repositories\Doctrine\UsuariosRepository;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class Authenticate
{
    public function __construct(protected Auth $auth){}

    public function handle($request, Closure $next, $guard = null)
    {
        try {
            if(is_null(\Auth::user())) {
                throw new \Exception();
            }

            return $next($request);
        } catch (\Exception $e) {
            return response()->json('Não autorizado', 401);
        }
    }
}
