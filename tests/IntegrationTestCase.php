<?php

use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

abstract class IntegrationTestCase extends LumenTestCase
{
    private bool $databaseJaConfigurado = false;
    protected EntityManagerInterface $em;
    private static SchemaTool $schemaTool;
    private static array $metadatas;

    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    abstract protected function modelsToTables(): array;

    protected function createTablesByModels(string ...$models)
    {
        self::$schemaTool = new SchemaTool($this->em);
        self::$metadatas = array_map(function($model) {
            return $this->em->getClassMetadata($model);
        }, $models);
        self::$schemaTool->createSchema(self::$metadatas);
    }

    public function setUp(): void
    {
        parent::setUp();
        if(!$this->databaseJaConfigurado) {
            $this->em = app()->make(EntityManagerInterface::class);
            $this->createTablesByModels(...$this->modelsToTables());
            $this->databaseJaConfigurado = true;
        }
        $this->em->beginTransaction();
    }

    public static function tearDownAfterClass(): void
    {
        self::$schemaTool->dropSchema(self::$metadatas);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->em->rollback();
    }
}
