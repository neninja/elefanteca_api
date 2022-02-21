<?php

use Doctrine\ORM\EntityManagerInterface as EntityManager;

use Core\Models\{
    Usuario,
    Livro,
    Autor,
};

use Core\Services\Emprestimo\{
    CadastroUsuarioService,
    CadastroLivroService,
    CadastroAutorService,
};

use App\Repositories\Doctrine\{
    UsuariosRepository,
    LivrosRepository,
    AutoresRepository,
};

use App\Adapters\{
    LumenCryptProvider,
};

abstract class IntegrationTestCase extends \PHPUnit\Framework\TestCase
{
    use Fake;
    use Doctrine;

    private static EntityManager    $em;

    public static function setUpBeforeClass(): void
    {
        self::databaseInitialConfigProccess();
    }

    public function setUp(): void
    {
        parent::setUp();
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
        self::$em = (new App\Repositories\Doctrine\EntityManagerFactory())->get();

        self::createDatabase();
    }

    protected function persistidoById(
        string $namespace,
        int $id,
        string $column = 'id'
    ) {
        return $this->databaseFindById($namespace, $id);
    }

    protected function factory(string $namespace)
    {
        if(preg_match('/Repository$/', $namespace)) {
            return new $namespace(self::$em);
        }

        switch($namespace){
        case LumenCryptProvider::class:
            return new $namespace();
        case CadastroAutorService::class:
            return new CadastroAutorService(
                $this->factory(AutoresRepository::class),
            );
        }

        return new $namespace;
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

    protected function databaseQuery(string $query)
    {
        return $this->doctrineExecuteQuery(self::$em, $query);
    }

    protected function databaseFindById(string $namespace, int $id)
    {
        return $this->doctrineFindById(self::$em, $namespace, $id);
    }
}
