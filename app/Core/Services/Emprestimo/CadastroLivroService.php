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
        string $quantidade,
        ?int   $id = null,
    ): Livro {
        $autor = $this->autoresRepo->findById($idAutor);

        $l = null;

        if(!is_null($id)) {
            $l = $this->livrosRepo->findById($id);
            $l->titulo      = $titulo;
            $l->autor       = $autor;
            $l->quantidade  = $quantidade;
        } else {
            $l = new Livro(
                titulo:     $titulo,
                autor:      $autor,
                quantidade: $quantidade,
            );
        }

        return $this->livrosRepo->save($l);
    }
}
