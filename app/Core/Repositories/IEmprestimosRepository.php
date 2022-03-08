<?php

namespace Core\Repositories;

use Core\Models\Emprestimo;

interface IEmprestimosRepository
{
    public function save(Emprestimo $e): Emprestimo;
    public function findById(int $id): ?Emprestimo;
    public function findBy(array $condition, int $page, int $limit): array;
    public function findNaoDevolvidosByIdLivro(
        int $idLivro, int $page, int $limit
    ): array;
}

