<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function store(Request $request)
    {
        $nome   = $request->input('name');
        $cpf    = $request->input('cpf');
        $senha  = $request->input('password');
        $email  = $request->input('email');

        return response()
            ->json([
                'name'      => 'Sally',
                'cpf'       => '17427351002',
                'password'  => 'djiajdij34214',
                'email'     => 'sally@foo.com',
            ]);
    }
}
