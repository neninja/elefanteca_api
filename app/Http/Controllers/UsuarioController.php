<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Core\Services\{
    Usuario\CadastroUsuarioService,
};

class UsuarioController extends Controller
{
    public function __construct(
        private CadastroUsuarioService $cadastroService
    ) {}

    public function store(Request $request)
    {
        $nome   = $request->input('name');
        $cpf    = $request->input('cpf');
        $senha  = $request->input('password');
        $email  = $request->input('email');

        $this->cadastroService->execute(
            nome: $nome,
            cpf: $cpf,
            senha: $senha,
            email: $email,
        );

        return response()
            ->json([
                'name'      => 'Sally',
                'cpf'       => '17427351002',
                'password'  => 'djiajdij34214',
                'email'     => 'sally@foo.com',
            ]);
    }
}
