<?php

namespace App\Repositories\Doctrine;

use Doctrine\ORM\EntityManagerInterface;

use Core\Models\Usuario;

class UsuariosRepository implements \Core\Repositories\IUsuariosRepository
{
    function __construct(
        private EntityManagerInterface $em
    ) {}

    public function save(Usuario $u): Usuario
    {
        $this->em->persist($u);
        $this->em->flush();
        return $u;
    }

    public function findById(int $id): Usuario
    {
        return $this->em->find(Usuario::class, $id);
    }
}
