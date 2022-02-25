<?php

namespace Core\Models;

use Core\Models\{
    Autor
};

class Livro
{
    protected bool $ativo;
    public \DateTimeImmutable   $created_at;
    public \DateTime            $updated_at;

    public function __construct(
        private     ?int    $id = null,
        public      string  $titulo,
        public      Autor   $autor,
        public      int     $quantidade,
    ) {
        $this->ativo = true;
    }

    public function getId() {
        return $this->id;
    }

    public function ativar() {
        $this->ativo = true;
        return $this;
    }

    public function inativar() {
        $this->ativo = false;
        return $this;
    }

    public function getAtivo() {
        return $this->ativo;
    }
}

