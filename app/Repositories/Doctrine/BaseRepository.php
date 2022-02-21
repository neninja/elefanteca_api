<?php

namespace App\Repositories\Doctrine;

use Doctrine\ORM\EntityManagerInterface;

abstract class BaseRepository
{
    protected string $model;

    function __construct(
        protected EntityManagerInterface $em
    ) {}

    protected function base_save($entity)
    {
        if(is_null($entity->getId())) {
            $this->em->persist($entity);
        } else {
            $this->em->merge($entity);
        }
        $this->em->flush();
        return $entity;
    }

    protected function base_findById(int $id)
    {
        return $this->em->find($this->model, $id);
    }

    protected function base_findBy(array $condition): array
    {
        return $this
            ->em
            ->getRepository($this->model)
            ->findBy($condition);
    }

    protected function base_findOneBy(array $condition)
    {
        return $this
            ->em
            ->getRepository($this->model)
            ->findOneBy($condition);
    }
}

