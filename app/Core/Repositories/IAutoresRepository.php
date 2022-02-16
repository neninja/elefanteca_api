<?php

namespace Core\Repositories;

use Core\Models\Autor;

interface IAutoresRepository
{
    public function save(Autor $e): Autor;
    public function findById(int $id): Autor;
}

