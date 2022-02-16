<?php

namespace Core\Models;

class Autor
{
    public function __construct(
        private     ?int    $id = null,
        public      string  $nome,
    ) {}

    public function getId() {
        return $this->id;
    }
}

