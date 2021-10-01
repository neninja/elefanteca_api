<?php

use Doctrine\ORM\EntityManagerInterface;

trait Doctrine {
    function DoctrineFindById(
        EntityManagerInterface $em,
        $model,
    ) {
        // $stmt = self::$em->getConnection()->prepare("SELECT * FROM usuarios");
        // $stmt->execute();
        // var_dump($stmt->fetchAll());
        return $em->find($model::class, $model->getId());
    }
}
