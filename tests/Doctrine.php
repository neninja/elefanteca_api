<?php

use Doctrine\ORM\EntityManagerInterface;

use Doctrine\ORM\Tools\SchemaTool;

use Core\Models\{
    Usuario,
    Livro,
    Autor,
};


trait Doctrine {
    public function doctrineFindById(
        EntityManagerInterface $em,
        string $namespace,
        int $id,
    ) {
        return $em->find($namespace, $id);
    }

    public function doctrineExecuteQuery(
        EntityManagerInterface $em,
        string $query,
    ) {
        $stmt = self::$em
            ->getConnection()
            ->prepare($query);

        $execute = $stmt->execute();
        return $execute->fetchAll();
    }

    public static function doctrineGetMetadatas(
        EntityManagerInterface $em
    ) {
        $models = [
            Usuario::class,
            Livro::class,
            Autor::class,
        ];

        return array_map(function($model) use ($em) {
            return $em->getClassMetadata($model);
        }, $models);
    }

    public static function doctrineCreateDatabase(
        EntityManagerInterface $em
    ) {
        // Clear Doctrine to be safe
        $em->clear();

        // Schema Tool to process our entities
        $tool = new SchemaTool($em);
        $classes = $em->getMetaDataFactory()->getAllMetaData();

        // Drop all classes and re-build them for each test case
        $tool->dropSchema($classes);
        $tool->createSchema($classes);
    }

    public static function doctrineDeleteDatabase(
        EntityManagerInterface $em
    ) {
        // Clear Doctrine to be safe
        $em->clear();

        // Schema Tool to process our entities
        $tool = new SchemaTool($em);
        $classes = $em->getMetaDataFactory()->getAllMetaData();

        // Drop all classes and re-build them for each test case
        $tool->dropSchema($classes);
    }
}
