<?php

use Doctrine\ORM\EntityManagerInterface;

trait Doctrine {
    function doctrineFindById(
        EntityManagerInterface $em,
        $model,
    ) {
        return $em->find($model::class, $model->getId());
    }

    function doctrineExecuteQuery(
        EntityManagerInterface $em,
        string $query,
    ) {
        $stmt = self::$em
            ->getConnection()
            ->prepare($query);

        $stmt->execute();
        return $stmt->fetchAll();
    }
}
