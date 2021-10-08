<?php

use Laravel\Lumen\Testing\TestCase;

use Doctrine\ORM\EntityManagerInterface as EntityManager;

use App\Repositories\Doctrine\{
    UsuariosRepository,
};
use App\Adapters\{
    LumenCryptProvider,
};

abstract class LumenTestCase extends TestCase
{
    use Doctrine;

    private static array            $dynamicSetUp;
    private static EntityManager    $em;

    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

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
        // pega conexão singleton que será utilizada na app
        self::$em = app()->make(EntityManager::class);

        self::createDatabase();
        self::$dynamicSetUp = [];
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
