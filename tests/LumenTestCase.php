<?php

use Doctrine\ORM\EntityManagerInterface as EntityManager;

abstract class LumenTestCase extends \Laravel\Lumen\Testing\TestCase
{
    use Doctrine;

    private static array            $dynamicSetUp;
    private static EntityManager    $em;

    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    /*
     * O App deve ser instanciado (usando ->createApplication), e isso ocorre
     * APÓS ::setUpBeforeClass(), portando configurações que devem ser feitas
     * somente uma vez e dependem do app:
     *  1) Ainda devem ser estáticas
     *  2) Precisam estar inicialmente no array de instruções
     *  3) Serem removidas após a primeira execução
     */
    public static function setUpBeforeClass(): void
    {
        self::$dynamicSetUp = ['databaseInitialConfigProccess'];
    }

    public function setUp(): void
    {
        parent::setUp();

        foreach(self::$dynamicSetUp as $setup) {
            $this->$setup();
        }

        # THANKS: https://stackoverflow.com/a/26836634
        $connection = self::$em
            ->getConnection()
            ->getWrappedConnection();

        /* Asserts de database do Lumen */
        DB::connection()->setPdo($connection);

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
        /* Conexão singleton criada e utilizada no app pelo Lumen */
        self::$em = app()->make(EntityManager::class);

        self::createDatabase();

        // self::$dynamicSetUp = []; // deveria ser feita a configuração inicial somente uma vez
    }

    protected static function beginTransaction()
    {
        self::$em->beginTransaction();
    }

    protected static function rollbackTransaction()
    {
        self::$em->rollback();
        self::$em->clear();
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
        return app()->make($namespace);
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
