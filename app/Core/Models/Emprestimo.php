<?php

namespace Core\Models;

class Emprestimo
{
    protected bool $ativo;

    public \DateTimeImmutable   $created_at;
    public \DateTime            $updated_at;

    public \DateTimeImmutable   $dataEntregaPrevista;
    public ?\DateTimeImmutable  $dataEntregaRealizada = null;

    public function __construct(
        private     ?int                $id = null,
        public      Livro               $livro,
        public      Usuario             $usuarioMembro,
        public      Usuario             $usuarioColaborador,
        public     \DateTimeImmutable   $dataEmprestimo,
    ) {
        $dataEntregaPrevista = \DateTime::createFromImmutable($this->dataEmprestimo);
        $dataEntregaPrevista->modify('2 weeks');

        $this->dataEntregaPrevista = \DateTimeImmutable::createFromMutable(
            $dataEntregaPrevista
        );

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

