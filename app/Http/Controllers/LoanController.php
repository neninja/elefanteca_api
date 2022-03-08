<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\LoanResource;

use Core\Services\{
    Emprestimo\EmprestimoService,
};

use Core\Repositories\{
    IEmprestimosRepository,
};

class LoanController extends Controller
{
    public function __construct(
        private EmprestimoService $emprestimoService,
        private IEmprestimosRepository $emprestimosRepository,
    ) {}

    /**
     * @OA\Get(
     *     tags={"empréstimo"},
     *     path="/api/loans/{id}",
     *     description="Exibição de 1 empréstimo",
     *     security={{"JWT":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id empréstimo",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *     ),
     *     @OA\Response(response="2XX", description="OK"),
     * )
     */
    public function show(int $id, Request $r)
    {
        $e = $this->findOrFail($id);

        return new LoanResource($e);
    }

    /**
     * @OA\Get(
     *     tags={"empréstimo"},
     *     path="/api/loans",
     *     description="Listagem de empréstimos",
     *     security={{"JWT":{}}},
     *     @OA\Parameter(
     *         name="bookId",
     *         in="query",
     *         description="Livro",
     *         @OA\Schema(type="integer", example=1),
     *     ),
     *     @OA\Parameter(
     *         name="memberId",
     *         in="query",
     *         description="Usuário do membro",
     *         @OA\Schema(type="integer", example=1),
     *     ),
     *     @OA\Parameter(
     *         name="collaboratorId",
     *         in="query",
     *         description="Usuário do colaborador",
     *         @OA\Schema(type="integer", example=2),
     *     ),
     *     @OA\Response(response="2XX", description="OK"),
     * )
     */
    public function index(Request $r)
    {
        $page = $r->page ?? 1;

        $condition = [];

        $condition = $this->getArrayOfRequestedValues($r, [
            'bookId'                => 'livro',
            'memberId'              => 'usuarioMembro',
            'collaboratorId'        => 'usuarioColaborador'
        ]);

        $e = $this->emprestimosRepository->findBy($condition, $page);

        return LoanResource::collection($e);
    }

    /**
     * @OA\Post(
     *     tags={"empréstimo"},
     *     path="/api/loans",
     *     description="Cadastro de empréstimo",
     *     security={{"JWT":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(mediaType="application/json;charset=UTF-8",
     *             @OA\Schema(
     *                 required={"bookId", "memberId", "collaboratorId"},
     *                 @OA\Property(
     *                      property="bookId",
     *                      type="integer",
     *                      example=1,
     *                 ),
     *                 @OA\Property(
     *                      property="memberId",
     *                      type="integer",
     *                      example=1,
     *                 ),
     *                 @OA\Property(
     *                      property="collaboratorId",
     *                      type="integer",
     *                      example=2,
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
            'bookId'         => 'required',
            'memberId'       => 'required',
            'collaboratorId' => 'required',
        ]);

        $e = $this->emprestimoService->execute(
            idLivro:        $r->bookId,
            idMembro:       $r->memberId,
            idColaborador:  $r->collaboratorId,
        );

        return new LoanResource($e);
    }

    private function findOrFail(int $id)
    {
        $e = $this->emprestimosRepository->findById($id);

        if(is_null($e)) {
            abort(404);
        }

        return $e;
    }
}

