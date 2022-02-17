<?php

namespace Core\Services\Emprestimo;

use Core\Models\{
    Livro,
    Autor,
};

use Core\Repositories\{
    ILivrosRepository,
    IAutoresRepository,
};

class CadastroLivroService
{
    function __construct(
        private ILivrosRepository $livrosRepo,
        private IAutoresRepository $autoresRepo,
    ){}

    public function execute(
        string $titulo,
        string $idAutor,
        string $quantidade
    ): Livro {
        $autor = $this->autoresRepo->findById($idAutor);
        $l = new Livro(
            titulo:     $titulo,
            autor:      $autor,
            quantidade: $quantidade,
        );

        return $this->livrosRepo->save($l);
    }
}
