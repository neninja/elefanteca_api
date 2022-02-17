<?php

namespace App\Repositories\Doctrine;

use Doctrine\ORM\EntityManagerInterface;

use Core\Models\Livro;

class LivrosRepository implements \Core\Repositories\ILivrosRepository
{
    function __construct(
        private EntityManagerInterface $em
    ) {}

    public function save(Livro $e): Livro
    {
        $this->em->persist($e);
        $this->em->flush();
        return $e;
    }

    public function findById(int $id): ?Livro
    {
        return $this->em->find(Livro::class, $id);
    }
}

