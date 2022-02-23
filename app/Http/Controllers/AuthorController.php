<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    public function index(Request $r)
    {
        $page = $r->page ?? 1;

        $a = $this
            ->autoresRepository
            ->findBy([], $page);

        return ['data' => $a];
    }

    /**
     * @OA\Post(
     *     tags={"autor"},
     *     path="/api/authors",
     *     description="Cadastro de usuário",
     *     security={{"JWT":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(mediaType="application/json;charset=UTF-8",
     *         @OA\Schema(
     *             @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  example="Edgar Allan Poe"
     *             )
     *         ),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Autor criado")
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

        return response()
            ->json([
                'id'    => $a->getId(),
                'name'  => $a->nome,
            ]);
    }

    /**
     * @OA\Put(
     *     tags={"autor"},
     *     path="/api/authors/{id}",
     *     description="Cadastro de usuário",
     *     security={{"JWT":{}}},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="int", value="1", summary="Id autor"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(mediaType="application/json;charset=UTF-8",
     *         @OA\Schema(
     *             @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  example="Edgar Allan Poe"
     *             )
     *         ),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Autor criado")
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

        return response()
            ->json([
                'id'    => $a->getId(),
                'name'  => $a->nome,
            ]);
    }
}
