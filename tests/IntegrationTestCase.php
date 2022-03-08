<?php

use Doctrine\ORM\EntityManagerInterface as EntityManager;

use Core\Models\{
    Usuario,
    Livro,
    Autor,
};

use Core\Services\Usuario\{
    CadastroUsuarioService,
};

use Core\Services\Emprestimo\{
    CadastroLivroService,
    CadastroAutorService,
    EmprestimoService,
};

use App\Repositories\Doctrine\{
    UsuariosRepository,
    LivrosRepository,
    AutoresRepository,
    EmprestimosRepository,
};

use \Core\Providers\{
    ICriptografiaProvider,
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
        case ICriptografiaProvider::class:
            $stub = $this->createMock(ICriptografiaProvider::class);

            $stub
                ->method('encrypt')
                ->will($this->returnCallBack(function($d) {
                    return $d."+";
                }));

            $stub
                ->method('decrypt')
                ->will($this->returnCallBack(function($d) {
                    return substr($d, 1);
                }));

            return $stub;
        case CadastroUsuarioService::class:
            return new CadastroUsuarioService(
                $this->factory(UsuariosRepository::class),
                $this->factory(ICriptografiaProvider::class),
            );
        case CadastroLivroService::class:
            return new CadastroLivroService(
                $this->factory(LivrosRepository::class),
                $this->factory(AutoresRepository::class),
            );
        case CadastroAutorService::class:
            return new CadastroAutorService(
                $this->factory(AutoresRepository::class),
            );
        case EmprestimoService::class:
            return new EmprestimoService(
                $this->factory(EmprestimosRepository::class),
                $this->factory(LivrosRepository::class),
                $this->factory(UsuariosRepository::class),
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
