<?php

namespace Core\Services\Emprestimo;

use Core\Models\Emprestimo;

use Core\Repositories\{
    IEmprestimosRepository,
};

use Core\Traits\Clock;

use Core\Exceptions\ValidationException;

class DevolucaoService
{
    use Clock;

    function __construct(
        private IEmprestimosRepository $emprestimosRepo,
    ){}

    public function execute(int $idEmprestimo): Emprestimo
    {
        $e = $this->emprestimosRepo->findById($idEmprestimo);

        if(is_null($e)) {
            throw new ValidationException("Empréstimo indisponível", $idEmprestimo);
        }

        if(!is_null($e->dataEntregaRealizada) || !$e->getAtivo()) {
            throw new ValidationException("Empréstimo indisponível", $idEmprestimo);
        }

        $e->dataEntregaRealizada = new \DateTimeImmutable();

        return $this->emprestimosRepo->save($e);
    }
}

