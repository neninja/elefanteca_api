<?php

use Core\Models\Usuario;

class E2ETestCase extends LumenTestCase
{
    use Doctrine;

    private static $em;
    private bool $conexaoAtualiza = false;

    public static function setUpBeforeClass(): void
    {
        self::$em = (new \App\Repositories\Doctrine\EntityManagerFactory())->get();
        self::criaBanco(self::$em);
    }

    private static function criaBanco($em)
    {
        $metadata = $em->getClassMetadata(Usuario::class);
        $schemaTool = (new \Doctrine\ORM\Tools\SchemaTool($em))
            ->createSchema([$metadata]);
    }

    public function setUp(): void
    {
        parent::setUp();

        if(!$this->conexaoAtualiza) {
            # THANKS: https://stackoverflow.com/a/26836634
            $connection = self::$em
                ->getConnection()
                ->getWrappedConnection();

            DB::connection()->setPdo($connection);
            $this->conexaoAtualiza = true;
        }

        self::$em->beginTransaction();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        self::$em->rollback();
    }
}
