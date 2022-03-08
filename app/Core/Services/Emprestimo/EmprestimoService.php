<?php

namespace Core\Services\Emprestimo;

use Core\Models\Emprestimo;

use Core\Repositories\{
    IEmprestimosRepository,
    IUsuariosRepository,
    ILivrosRepository,
};

use Core\Exceptions\ValidationException;

use Core\Traits\Clock;

class EmprestimoService
{
    use Clock;

    function __construct(
        private IEmprestimosRepository $emprestimosRepo,
        private ILivrosRepository $livrosRepo,
        private IUsuariosRepository $usuariosRepo,
    ){}

    public function execute(
        int $idLivro,
        int $idMembro,
        int $idColaborador,
    ): Emprestimo {
        $livro       = $this->livrosRepo->findById($idLivro);
        $membro      = $this->usuariosRepo->findById($idMembro);
        $colaborador = $this->usuariosRepo->findById($idColaborador);

        $livrosEmprestados = count(
            $this->emprestimosRepo->findNaoDevolvidosByIdLivro($idLivro)
        );

        $livrosEmprestados = $this
            ->emprestimosRepo
            ->findNaoDevolvidosByIdLivro($idLivro);
        $totalDeLivrosEmprestados = count($livrosEmprestados);

        if($totalDeLivrosEmprestados >= $livro->quantidade) {
            throw new ValidationException("Livro indisponível", $idLivro);
        }

        $e = new Emprestimo(
            livro: $livro,
            usuarioMembro: $membro,
            usuarioColaborador: $colaborador,
            dataEmprestimo: $this->now(),
        );

        return $this->emprestimosRepo->save($e);
    }
}
