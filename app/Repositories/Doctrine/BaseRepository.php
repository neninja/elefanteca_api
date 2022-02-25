<?php

namespace App\Repositories\Doctrine;

use Doctrine\ORM\EntityManagerInterface;

abstract class BaseRepository
{
    protected string $model;

    function __construct(
        protected EntityManagerInterface $em
    ) {}

    protected function base_save($entity, bool $timestamp = true)
    {
        if(is_null($entity->getId())) {
            if($timestamp) {
                $entity->created_at = new \DateTimeImmutable();
                $entity->updated_at = new \DateTime();
            }

            $this->em->persist($entity);
        } else {
            if($timestamp) {
                $entity->updated_at = new \DateTime();
            }

            $this->em->merge($entity);
        }
        $this->em->flush();
        return $entity;
    }

    protected function base_findById(int $id)
    {
        return $this->em->find($this->model, $id);
    }

    protected function base_findBy(
        array $condition, int $limit = 10, int $page = 1
    ): array {
        return $this
            ->em
            ->getRepository($this->model)
            ->findBy(
                $condition,
                [],
                $limit,
                $limit * ($page - 1) // após $offset, listar $limit
            );
    }

    protected function base_findOneBy(array $condition)
    {
        return $this
            ->em
            ->getRepository($this->model)
            ->findOneBy($condition);
    }

    protected function base_qb()
    {
        return $this
            ->em
            ->getRepository($this->model)
            ->createQueryBuilder('t');
    }

    protected function base_findByWithLikeEqual(
        array $likeProps,
        array $allConditions,
        int $limit = 10,
        int $page = 1,
    ) {
        $props = array_keys($allConditions);

        $likeConditions = array_filter(
            $props,
            fn($p) => in_array($p, $likeProps)
        );

        if(empty($likeConditions)) {
            return $this->base_findBy($allConditions, $limit, $page);
        }

        $qb = $this->base_qb();

        $equalCondition = array_diff($props, $likeConditions);

        foreach($likeConditions as $p) {
            $qb->where("t.$p LIKE :$p")
               ->setParameter($p, "%{$allConditions[$p]}%");
        }

        foreach($equalCondition as $p) {
            $qb->where("t.$p = :$p")
               ->setParameter($p, $allConditions[$p]);
        }

        return $qb->getQuery()->getResult();
    }

    protected function base_delete(int $id)
    {
        $e = $this->base_findById($id);
        $this->em->remove($e);
        $this->em->flush();
    }
}

