<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\AuthorResource;

use Core\Services\{
    Emprestimo\CadastroAutorService,
};

use Core\Repositories\{
    IAutoresRepository,
};

class AuthorController extends Controller
{
    public function __construct(
        private CadastroAutorService $cadastroService,
        private IAutoresRepository $autoresRepository,
    ) {}

    /**
     * @OA\Get(
     *     tags={"autor"},
     *     path="/api/authors/{id}",
     *     description="Exibição de 1 autor",
     *     security={{"JWT":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id autor",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *     ),
     *     @OA\Response(response="2XX", description="OK"),
     * )
     */
    public function show(int $id, Request $r)
    {
        $a = $this->autoresRepository->findById($id);

        return new AuthorResource($a);
    }

    /**
     * @OA\Get(
     *     tags={"autor"},
     *     path="/api/authors",
     *     description="Listagem de autores",
     *     security={{"JWT":{}}},
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Nome parcial do autor",
     *         @OA\Schema(type="string", example="Allan"),
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

        $a = $this->autoresRepository->findBy($condition, $page);

        return AuthorResource::collection($a);
    }

    /**
     * @OA\Post(
     *     tags={"autor"},
     *     path="/api/authors",
     *     description="Cadastro de autor",
     *     security={{"JWT":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(mediaType="application/json;charset=UTF-8",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      example="Edgar Allan Poe",
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
            'name' => 'required',
        ]);

        $a = $this->cadastroService->execute(
            nome: $r->name,
        );

        return new AuthorResource($a);
    }

    /**
     * @OA\Put(
     *     tags={"autor"},
     *     path="/api/authors/{id}",
     *     description="Edição de autor",
     *     security={{"JWT":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id autor",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(mediaType="application/json;charset=UTF-8",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      example="Edgar Allan Poe",
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(response="2XX", description="OK"),
     * )
     */
    public function update(int $id, Request $r)
    {
        $this->validate($r, [
            'name' => 'required',
        ]);

        $a = $this->cadastroService->execute(
            id:     $id,
            nome:   $r->name,
        );

        return new AuthorResource($a);
    }

    /**
     * @OA\Delete(
     *     tags={"autor"},
     *     path="/api/authors/{id}",
     *     description="Deleção de autor",
     *     security={{"JWT":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id autor",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *     ),
     *     @OA\Response(response="2XX", description="OK"),
     * )
     */
    public function destroy(int $id)
    {
        $this->autoresRepository->delete($id);

        return response()->json('', 204);
    }
}
