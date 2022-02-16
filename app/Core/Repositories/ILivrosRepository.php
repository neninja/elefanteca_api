<?php

namespace Core\Repositories;

use Core\Models\Livro;

interface ILivrosRepository
{
    public function save(Livro $e): Livro;
    public function findById(int $id): Livro;
}

