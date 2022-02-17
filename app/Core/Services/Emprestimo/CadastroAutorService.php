<?php

namespace Core\Services\Emprestimo;

use Core\Models\{
    Autor,
};

use Core\Repositories\{
    IAutoresRepository,
};

class CadastroAutorService
{
    function __construct(
        private IAutoresRepository $repo,
    ){}

    public function execute(
        string $nome,
    ): Autor {
        $a = new Autor(
            nome: $nome,
        );

        return $this->repo->save($a);
    }
}

