<?php

use Doctrine\ORM\EntityManagerInterface;

trait Doctrine {
    function doctrineFindById(
        EntityManagerInterface $em,
        string $namespace,
        int $id,
    ) {
        return $em->find($namespace, $id);
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
