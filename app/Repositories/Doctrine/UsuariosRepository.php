<?php

namespace App\Repositories\Doctrine;

use Doctrine\ORM\EntityManagerInterface;

use Core\Models\{
    Usuario,
    Email,
};

class UsuariosRepository extends BaseRepository implements \Core\Repositories\IUsuariosRepository
{
    protected string $model = Usuario::class;

    public function save(Usuario $e): Usuario
    {
        return $this->base_save($e);
    }

    public function findById(int $id): ?Usuario
    {
        return $this->base_findById($id);
    }

    public function findByEmail(string $email): ?Usuario
    {
        return $this->base_findOneBy(['email' => new Email($email)]);
    }
}
