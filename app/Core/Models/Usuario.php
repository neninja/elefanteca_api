<?php

namespace Core\Models;

use Core\Models\{
    Email,
    CPF,
};

class Usuario
{
    protected bool $ativo;

    public function __construct(
        private     ?int    $id = null,
        public      string  $nome,
        public      CPF     $cpf,
        private     string  $senha, // criptografada
        public      Email   $email,
    ) {
        $this->ativo = true;
    }

    public function getId() {
        return $this->id;
    }

    public function getSenha() {
        return $this->senha;
    }

    public function ativar() {
        $this->ativo = true;
        return $this;
    }

    public function inativar() {
        $this->ativo = false;
        return $this->senha;
    }

    public function getAtivo() {
        return $this->ativo;
    }
}
