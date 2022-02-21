<?php

namespace App\Repositories\Doctrine;

use Doctrine\ORM\EntityManagerInterface;

use Core\Models\Autor;

class AutoresRepository extends BaseRepository implements \Core\Repositories\IAutoresRepository
{
    protected string $model = Autor::class;

    public function save(Autor $e): Autor
    {
        return $this->base_save($e);
    }

    public function findById(int $id): ?Autor
    {
        return $this->base_findById($id);
    }
}

