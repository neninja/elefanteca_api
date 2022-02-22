<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Support\Facades\Gate;

use Core\Models\Papel;

class HasRole
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next, ...$roles)
    {
        try {
            foreach($roles as $role) {
                if(Gate::check('papel', Papel::$$role)) {
                    return $next($request);
                }
            }

            throw new \Exception();
        } catch (\Exception $e) {
            return response()->json('Não autorizado', 401);
        }
    }
}

