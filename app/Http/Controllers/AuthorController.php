<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Core\Services\{
    Emprestimo\CadastroAutorService,
};

class AuthorController extends Controller
{
    public function __construct(
        private CadastroAutorService $cadastroService
    ) {}

    /**
     * @OA\Post(
     *     tags={"autor"},
     *     path="/api/authors",
     *     description="Cadastro de usuário",
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
    public function store(Request $request)
    {
        $nome = $request->input('name');

        $a = $this->cadastroService->execute(
            nome:   $nome,
        );

        return response()
            ->json([
                'id'        => $a->getId(),
                'name'      => $a->nome,
            ]);
    }
}
