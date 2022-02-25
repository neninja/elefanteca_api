<?php

namespace Core\Models;

class Autor
{
    public \DateTimeImmutable   $created_at;
    public \DateTime            $updated_at;

    public function __construct(
        private     ?int    $id = null,
        public      string  $nome,
    ) {}

    public function getId() {
        return $this->id;
    }
}

