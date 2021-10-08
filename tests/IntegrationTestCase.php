<?php

use Doctrine\ORM\EntityManagerInterface as EntityManager;

use App\Repositories\Doctrine\{
    UsuariosRepository,
};
use App\Adapters\{
    LumenCryptProvider,
};

abstract class IntegrationTestCase extends LumenTestCase
{
    use Doctrine;

    private static string           $currentSetUpDatabase;
    private static EntityManager    $em;

    public static function setUpBeforeClass(): void
    {
        self::$currentSetUpDatabase = 'databaseInitialConfigProccess';
    }

    public function setUp(): void
    {
        parent::setUp();

        $currentSetUpDatabase = self::$currentSetUpDatabase;
        $this->$currentSetUpDatabase();

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

    protected static function databaseInitialConfigProccess()
    {
        // pega conexão singleton que será utilizada na app
        self::$em = app()->make(EntityManager::class);

        self::createDatabase();
        self::$currentSetUpDatabase = 'databasePostConfigProccess';
    }

    protected static function databasePostConfigProccess()
    {
        // limpa identity map do doctrine, evitando
        // inconsistência de dados novos ou alterados
        // entre testes
        self::$em->clear();
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

    protected function databaseQuery(string $query)
    {
        return $this->doctrineExecuteQuery(self::$em, $query);
    }

    protected function databaseFindById(string $namespace, int $id)
    {
        return $this->doctrineFindById(self::$em, $namespace, $id);
    }
}
