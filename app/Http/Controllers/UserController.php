<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Gate;
use Core\Models\Papel;

use Core\Services\{
    Usuario\CadastroUsuarioService,
};

class UserController extends Controller
{
    public function __construct(
        private CadastroUsuarioService $cadastroService
    ) {}

    /**
     * @OA\Post(
     *     tags={"usuário"},
     *     path="/api/users",
     *     description="Cadastro de usuário",
     *     security={{"JWT":{}}},
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
    public function store(Request $r)
    {
        $this->validate($r, [
            'name'      => 'required',
            'cpf'       => 'required',
            'email'     => 'required|email',
            'password'  => 'required',
        ]);

        $role = '';

        if(Gate::check('papel', Papel::$ADMIN)) {
            $role = $r->role ?? '';
        }

        $u = $this->cadastroService->execute(
            nome:   $r->name,
            cpf:    $r->cpf,
            senha:  $r->password,
            email:  $r->email,
            papel:  $role,
        );

        return response()
            ->json([
                'id'        => $u->getId(),
                'name'      => $u->nome,
                'cpf'       => $u->cpf->getNumero(),
                'email'     => $u->email->getEmail(),
                'role'      => $u->papel->get(),
            ]);
    }
}
