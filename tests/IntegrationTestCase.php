<?php

use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

use App\Repositories\Doctrine\{
    UsuariosRepository,
};
use App\Adapters\{
    LumenCryptProvider,
};

use Core\Models\{
    Usuario,
};

abstract class IntegrationTestCase extends LumenTestCase
{
    use Doctrine;

    private bool $databaseJaConfigurado = false;
    protected static EntityManagerInterface $em;
    private static SchemaTool $schemaTool;
    private static array $metadatas;

    protected static function createDatabase()
    {
        $models = [
            Usuario::class
        ];
        self::$schemaTool = new SchemaTool(self::$em);
        self::$metadatas = array_map(function($model) {
            return self::$em->getClassMetadata($model);
        }, $models);
        self::$schemaTool->createSchema(self::$metadatas);
    }

    protected static function deleteDatabase()
    {
        self::$schemaTool->dropSchema(self::$metadatas);
    }

    public function setUp(): void
    {
        parent::setUp();
        if(!$this->databaseJaConfigurado) {
            // pega conexão singleton que será utilizada na app
            self::$em = app()->make(EntityManagerInterface::class);

            self::createDatabase();
            $this->databaseJaConfigurado = true;
        }
        self::$em->beginTransaction();
    }

    public static function tearDownAfterClass(): void
    {
        self::deleteDatabase();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        self::$em->rollback();
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
