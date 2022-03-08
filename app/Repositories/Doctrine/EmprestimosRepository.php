<?php

namespace App\Repositories\Doctrine;

use Doctrine\ORM\EntityManagerInterface;

use Core\Models\Emprestimo;

class EmprestimosRepository extends BaseRepository implements \Core\Repositories\IEmprestimosRepository
{
    protected string $model = Emprestimo::class;

    public function save(Emprestimo $e): Emprestimo
    {
        return $this->base_save($e);
    }

    public function findById(int $id): ?Emprestimo
    {
        return $this->base_findById($id);
    }

    public function findBy(array $condition, int $page, int $limit = 10): array
    {
        return $this
            ->em
            ->getRepository($this->model)
            ->findBy(
                $condition,
                [],
                $limit,
                $limit * ($page - 1) // após $offset, listar $limit
            );
        return $this->base_findBy($condition, $limit, $page);
    }

    public function findNaoDevolvidosByIdLivro(
        int $idLivro, $limit = 10, $page = 1
    ): array {
        return $this->base_qb()
            ->where("t.livro = :idLivro and t.ativo = :ativo and t.dataEntregaRealizada is null")
            ->setParameter('idLivro', $idLivro)
            ->setParameter('ativo', true)
            ->getQuery()
            ->getResult();
    }
}
