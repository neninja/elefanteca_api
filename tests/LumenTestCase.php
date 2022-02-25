<?php

use Doctrine\ORM\EntityManagerInterface as EntityManager;

abstract class LumenTestCase extends \Laravel\Lumen\Testing\TestCase
{
    use Fake;
    use Doctrine;

    private static array            $dynamicSetUp;
    private static EntityManager    $em;

    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    /*
     * O App deve ser instanciado (usando ->createApplication), e isso ocorre
     * APÓS ::setUpBeforeClass() com parent::setUp(), portando as configurações
     * que devem ser feitas somente uma vez e dependem do app:
     *  1) Ainda devem ser estáticas
     *  2) Precisam estar inicialmente no array de instruções
     *  3) Serem removidas após a primeira execução
     */
    public static function setUpBeforeClass(): void
    {
        self::$dynamicSetUp = ['initialDynamicSetup'];
    }

    public function setUp(): void
    {
        parent::setUp();

        foreach(self::$dynamicSetUp as $setup) {
            $this->$setup();
        }

        /*
         * Precisa repassar a conexão para utilizar os asserts de banco de
         * dados do Lumen, mas por algum motivo ela se perde a cada teste.
         * Sendo necessário reforçá-la de maneira fixa no ->setUp
         */
        DB::connection()->setPdo(
            self::$em->getConnection()->getNativeConnection()
        );

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

    protected static function initialDynamicSetup()
    {
        self::$em = app()->make(EntityManager::class);

        self::createDatabase();

        self::$dynamicSetUp = ['dynamicSetUpMaintenance']; // cria database somente uma vez
    }

    protected function dynamicSetUpMaintenance()
    {
        /*
         * Reaproveita EntityManager criado em initialDynamicSetup para
         * utilizar o mesmo banco in memory
         */
        $this->app->instance(EntityManager::class, self::$em);
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
