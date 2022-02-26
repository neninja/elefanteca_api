<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Gate;
use Core\Models\Papel;

use App\Http\Resources\UserResource;

use Core\Services\{
    Usuario\CadastroUsuarioService,
};

use Core\Repositories\{
    IUsuariosRepository,
};

class UserController extends Controller
{
    public function __construct(
        private CadastroUsuarioService $cadastroService,
        private IUsuariosRepository $usuariosRepository,
    ) {}

    /**
     * @OA\Get(
     *     tags={"usuário"},
     *     path="/api/user/{id}",
     *     description="Exibição de 1 usuário",
     *     security={{"JWT":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id usuário",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *     ),
     *     @OA\Response(response="2XX", description="OK"),
     * )
     */
    public function show(int $id, Request $r)
    {
        $u = $this->usuariosRepository->findById($id);

        if(is_null($u)) {
            abort(404);
        }

        return new UserResource($u);
    }

    /**
     * @OA\Get(
     *     tags={"usuário"},
     *     path="/api/users",
     *     description="Listagem de usuários",
     *     security={{"JWT":{}}},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Nome parcial do usuário",
     *         @OA\Schema(type="string", example="Allan"),
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="Email parcial do usuário",
     *         @OA\Schema(type="string", example="example@foo.com"),
     *     ),
     *     @OA\Parameter(
     *         name="cpf",
     *         in="query",
     *         description="CPF do usuário",
     *         @OA\Schema(type="string", example="37128197060"),
     *     ),
     *     @OA\Parameter(
     *         name="role",
     *         in="query",
     *         description="Papel do usuário",
     *         @OA\Schema(
     *              type="string",
     *              enum={"membro", "colaborador", "admin"}
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Paginação",
     *         @OA\Schema(type="integer", example=1),
     *     ),
     *     @OA\Response(response="2XX", description="OK"),
     * )
     */
    public function index(Request $r)
    {
        $page = $r->page ?? 1;

        $condition = [];

        if(!is_null($r->name)) {
            $condition['nome'] = $r->name;
        }

        if(!is_null($r->cpf)) {
            $condition['cpf'] = $r->cpf;
        }

        if(!is_null($r->email)) {
            $condition['email'] = $r->email;
        }

        if(!is_null($r->role)) {
            $condition['papel'] = $r->role;
        }

        $u = $this->usuariosRepository->findBy($condition, $page);

        return UserResource::collection($u);
    }

    /**
     * @OA\Post(
     *     tags={"usuário"},
     *     path="/api/users",
     *     description="Cadastro de usuário",
     *     security={{"JWT":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(mediaType="application/json;charset=UTF-8",
     *             @OA\Schema(
     *                 required={"name", "cpf", "password", "email"},
     *                 @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      example="Diego",
     *                 ),
     *                 @OA\Property(
     *                      property="cpf",
     *                      type="string",
     *                      example="37128197060",
     *                 ),
     *                 @OA\Property(
     *                      property="password",
     *                      type="string",
     *                      example="19800507",
     *                 ),
     *                 @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      example="example@foo.com",
     *                 ),
     *                 @OA\Property(
     *                      property="role",
     *                      type="string",
     *                      example="membro",
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(response="2XX", description="OK"),
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

        return new UserResource($u);
    }

    /**
     * @OA\Put(
     *     tags={"usuário"},
     *     path="/api/users/{id}",
     *     description="Edição de usuário",
     *     security={{"JWT":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id usuário",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(mediaType="application/json;charset=UTF-8",
     *             @OA\Schema(
     *                 required={"name", "cpf", "password", "email"},
     *                 @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      example="Diego",
     *                 ),
     *                 @OA\Property(
     *                      property="cpf",
     *                      type="string",
     *                      example="37128197060",
     *                 ),
     *                 @OA\Property(
     *                      property="password",
     *                      type="string",
     *                      example="19800507",
     *                 ),
     *                 @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      example="example@foo.com",
     *                 ),
     *                 @OA\Property(
     *                      property="role",
     *                      type="string",
     *                      example="membro",
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(response="2XX", description="OK"),
     * )
     */
    public function update(int $id, Request $r)
    {
        abort(404); // TODO
    }
}
