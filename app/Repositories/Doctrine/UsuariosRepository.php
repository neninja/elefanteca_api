<?php

namespace App\Repositories\Doctrine;

use Doctrine\ORM\EntityManagerInterface;

use Core\Models\{
    Usuario,
    Email,
};

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

    public function findById(int $id): ?Usuario
    {
        return $this->em->find(Usuario::class, $id);
    }

    public function findBy(array $condition): array
    {
        return $this
            ->em
            ->getRepository(Usuario::class)
            ->findBy($condition);
    }

    public function findOneBy(array $condition): ?Usuario
    {
        return $this
            ->em
            ->getRepository(Usuario::class)
            ->findOneBy($condition);
    }

    public function findByEmail(string $email): ?Usuario
    {
        return $this->findOneBy(['email' => new Email($email)]);
    }
}
