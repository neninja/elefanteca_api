<?php

use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use Doctrine\ORM\EntityManagerInterface;

use App\Repositories\Doctrine\{
    UsuariosRepository,
};
use App\Adapters\{
    LumenCryptProvider,
};

abstract class IntegrationTestCase extends LumenTestCase
{
    use Doctrine;

    private static string $execDatabaseConfigProccess;
    protected static EntityManagerInterface $em;

    public static function setUpBeforeClass(): void
    {
        self::$execDatabaseConfigProccess = 'databaseConfigProccess';
    }

    public function setUp(): void
    {
        parent::setUp();

        $execDatabaseConfigProccess = self::$execDatabaseConfigProccess;
        $this->$execDatabaseConfigProccess();

        self::beginTransaction();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        self::rollbackTransaction();
    }

    public static function tearDownAfterClass(): void
    {
        self::deleteDatabase();
    }

    protected static function databaseConfigProccess()
    {
        // pega conexão singleton que será utilizada na app
        self::$em = app()->make(EntityManagerInterface::class);

        self::createDatabase();
        self::$execDatabaseConfigProccess = 'databasePostConfigProccess';
    }

    protected static function databasePostConfigProccess()
    {
        // tudo certo
    }

    protected static function beginTransaction()
    {
        self::$em->beginTransaction();
    }

    protected static function rollbackTransaction()
    {
        self::$em->rollback();
    }

    protected static function createDatabase()
    {
        self::doctrineCreateDatabase(self::$em);
    }

    protected static function deleteDatabase()
    {
        self::doctrineDeleteDatabase(self::$em);
    }

    protected function factory(string $namespace)
    {
        if(preg_match('/Repository$/', $namespace)) {
            return new $namespace(self::$em);
        }

        switch($namespace){
        case LumenCryptProvider::class:
            return new $namespace(self::$em);
            break;
        }

        return $namespace;
    }

    protected function databaseFindById(string $namespace, int $id)
    {
        return $this->doctrineFindById(self::$em, $namespace, $id);
    }
}
