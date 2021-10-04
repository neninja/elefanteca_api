<?php

use Doctrine\ORM\EntityManagerInterface as EntityManager;

class E2ETestCase extends LumenTestCase
{
    use Doctrine;

    private static string           $currentSetUpDatabase;
    private static EntityManager    $em;

    public static function setUpBeforeClass(): void
    {
        self::$currentSetUpDatabase = 'databaseConfigProccess';
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

    protected static function databaseConfigProccess()
    {
        // pega conexão singleton que será utilizada na app
        self::$em = app()->make(EntityManager::class);

        self::createDatabase();

        # THANKS: https://stackoverflow.com/a/26836634
        $connection = self::$em
            ->getConnection()
            ->getWrappedConnection();

        DB::connection()->setPdo($connection);

        self::$currentSetUpDatabase = 'databasePostConfigProccess';
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
}
