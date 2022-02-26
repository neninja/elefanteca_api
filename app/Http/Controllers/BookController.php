<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Core\Services\{
    Emprestimo\CadastroLivroService,
};

use Core\Repositories\{
    ILivrosRepository,
};

use App\Http\Resources\BookResource;

class BookController extends Controller
{
    public function __construct(
        private CadastroLivroService $cadastroService,
        private ILivrosRepository $livrosRepository,
    ) {}

    /**
     * @OA\Get(
     *     tags={"livro"},
     *     path="/api/books/{id}",
     *     description="Exibição de 1 livro",
     *     security={{"JWT":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id livro",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *     ),
     *     @OA\Response(response="2XX", description="OK"),
     * )
     */
    public function show(int $id, Request $r)
    {
        $l = $this->livrosRepository->findById($id);

        if(is_null($l)) {
            abort(404);
        }

        return new BookResource($l);
    }

    /**
     * @OA\Get(
     *     tags={"livro"},
     *     path="/api/books",
     *     description="Listagem de livros",
     *     security={{"JWT":{}}},
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Título parcial do livro",
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

        if(!is_null($r->title)) {
            $condition['titulo'] = $r->title;
        }

        $l = $this->livrosRepository->findBy($condition, $page);

        return BookResource::collection($l);
    }

    /**
     * @OA\Post(
     *     tags={"livro"},
     *     path="/api/books",
     *     description="Cadastro de livro",
     *     security={{"JWT":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(mediaType="application/json;charset=UTF-8",
     *             @OA\Schema(
     *                 required={"title", "author_id", "amount"},
     *                 @OA\Property(
     *                      property="title",
     *                      type="string",
     *                      example="Coração delator",
     *                 ),
     *                 @OA\Property(
     *                      property="author_id",
     *                      type="integer",
     *                      example=1,
     *                 ),
     *                 @OA\Property(
     *                      property="amount",
     *                      type="integer",
     *                      example=3,
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
            'title'     => 'required',
            'author_id' => 'required|integer',
            'amount'    => 'required|integer',
        ]);

        $l = $this->cadastroService->execute(
            titulo:     $r->title,
            idAutor:    $r->author_id,
            quantidade: $r->amount,
        );

        return new BookResource($l);
    }

    /**
     * @OA\Put(
     *     tags={"livro"},
     *     path="/api/books/{id}",
     *     description="Edição de livro",
     *     security={{"JWT":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id livro",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(mediaType="application/json;charset=UTF-8",
     *             @OA\Schema(
     *                 required={"title", "author_id", "amount"},
     *                 @OA\Property(
     *                      property="title",
     *                      type="string",
     *                      example="Coração delator",
     *                 ),
     *                 @OA\Property(
     *                      property="author_id",
     *                      type="integer",
     *                      example=1,
     *                 ),
     *                 @OA\Property(
     *                      property="amount",
     *                      type="integer",
     *                      example=3,
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
            'title'     => 'required',
            'author_id' => 'required|integer',
            'amount'    => 'required|integer',
        ]);

        $l = $this->cadastroService->execute(
            id:         $id,
            titulo:     $r->title,
            idAutor:    $r->author_id,
            quantidade: $r->amount,
        );

        return new BookResource($l);
    }

    /**
     * @OA\Delete(
     *     tags={"livro"},
     *     path="/api/books/{id}",
     *     description="Deleção de livro",
     *     security={{"JWT":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id livro",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *     ),
     *     @OA\Response(response="2XX", description="OK"),
     * )
     */
    public function destroy(int $id)
    {
        $l = $this->livrosRepository->findById($id);
        $l->inativar();
        $this->livrosRepository->save($l);

        return response()->json('', 204);
    }

    /**
     * @OA\Post(
     *     tags={"livro"},
     *     path="/api/books/{id}/activate",
     *     description="Reativação de livro",
     *     security={{"JWT":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id livro",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *     ),
     *     @OA\Response(response="2XX", description="OK"),
     * )
     */
    public function activate(int $id)
    {
        $l = $this->livrosRepository->findById($id);
        $l->ativar();
        $this->livrosRepository->save($l);

        return response()->json('', 204);
    }
}

