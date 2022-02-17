<?php

namespace App\Repositories\Doctrine;

use Doctrine\ORM\EntityManagerInterface;

use Core\Models\Autor;

class AutoresRepository implements \Core\Repositories\IAutoresRepository
{
    function __construct(
        private EntityManagerInterface $em
    ) {}

    public function save(Autor $e): Autor
    {
        $this->em->persist($e);
        $this->em->flush();
        return $e;
    }

    public function findById(int $id): ?Autor
    {
        return $this->em->find(Autor::class, $id);
    }
}

