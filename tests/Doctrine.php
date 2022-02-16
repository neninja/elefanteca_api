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

        $stmt->execute();
        return $stmt->fetchAll();
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
        $schemaTool = new SchemaTool($em);
        $schemaTool->createSchema(self::doctrineGetMetadatas($em));
    }

    public static function doctrineDeleteDatabase(
        EntityManagerInterface $em
    ) {
        $schemaTool = new SchemaTool($em);
        $schemaTool->dropSchema(self::doctrineGetMetadatas($em));
    }
}
