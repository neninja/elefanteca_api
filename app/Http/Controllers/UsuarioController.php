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

    /**
     * @OA\Post(
     *     tags={"usuário"},
     *     path="/api/users",
     *     description="Cadastro de usuário",
     *     @OA\RequestBody(
     *         @OA\MediaType(mediaType="application/json;charset=UTF-8",
     *         @OA\Schema(
     *             @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  example="Diego"
     *             ),
     *             @OA\Property(
     *                  property="cpf",
     *                  type="string",
     *                  example="37128197060"
     *             ),
     *             @OA\Property(
     *                  property="password",
     *                  type="string",
     *                  example="19800507"
     *             ),
     *             @OA\Property(
     *                  property="email",
     *                  type="string",
     *                  example="example@foo.com"
     *             )
     *         ),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Usuário criado")
     * )
     */
    public function store(Request $request)
    {
        $nome   = $request->input('name');
        $cpf    = $request->input('cpf');
        $senha  = $request->input('password');
        $email  = $request->input('email');

        $u = $this->cadastroService->execute(
            nome:   $nome,
            cpf:    $cpf,
            senha:  $senha,
            email:  $email,
        );

        return response()
            ->json([
                'name'      => $u->nome,
                'cpf'       => $u->cpf->formatado(),
                'email'     => $u->email->getEmail(),
            ]);
    }
}
