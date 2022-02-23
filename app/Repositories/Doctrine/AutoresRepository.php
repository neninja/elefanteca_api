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

    public function findBy(array $condition, int $page, int $limit = 10): array
    {
        return $this->base_findByWithLikeEqual(
            ['nome'],
            $condition,
            $limit,
            $page,
        );
        $props = array_keys($condition);

        $likeConditions = array_filter($props, function($p) {
            return $p === 'nome';
        });

        if(empty($likeConditions)) {
            return $this->base_findBy($condition, $limit, $page);
        }

        $qb = $this->base_qb();

        $equalCondition = array_diff($props, $likeConditions);

        foreach($likeConditions as $p) {
            $qb->where("t.$p LIKE :$p")
               ->setParameter($p, "%{$condition[$p]}%");
        }

        foreach($equalCondition as $p) {
            $qb->where("t.$p = :$p")
               ->setParameter($p, $condition[$p]);
        }

        return $qb->getQuery()->getResult();
    }
}

