<?php

namespace Core\Repositories;

use Core\Models\Usuario;

interface IUsuariosRepository
{
    public function save(Usuario $u): Usuario;
    public function findById(int $id): ?Usuario;
    public function findByEmail(string $email): ?Usuario;
    public function findByCpf(string $cpf): ?Usuario;
    public function findBy(array $condition, int $page, int $limit): array;
}
