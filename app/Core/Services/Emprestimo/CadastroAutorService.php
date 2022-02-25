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
        string  $nome,
        ?int    $id = null,
    ): Autor {
        $a = null;

        if(!is_null($id)) {
            $a = $this->repo->findById($id);
            $a->nome = $nome;
        } else {
            $a = new Autor(
                id:     $id,
                nome:   $nome,
            );
        }

        return $this->repo->save($a);
    }
}

