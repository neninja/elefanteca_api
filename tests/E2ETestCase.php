<?php

use Doctrine\ORM\EntityManagerInterface;
use Core\Models\Usuario;

class E2ETestCase extends LumenTestCase
{
    use Doctrine;

    private $em;
    private bool $appJaInicializado = false;

    private function criaBanco($em)
    {
        $metadata = $em->getClassMetadata(Usuario::class);
        $schemaTool = (new \Doctrine\ORM\Tools\SchemaTool($em))
            ->createSchema([$metadata]);
    }

    public function setUp(): void
    {
        parent::setUp();

        if(!$this->appJaInicializado) {
            $this->em = app()->make(EntityManagerInterface::class);
            $this->criaBanco($this->em);

            # THANKS: https://stackoverflow.com/a/26836634
            $connection = $this->em
                ->getConnection()
                ->getWrappedConnection();

            DB::connection()->setPdo($connection);
            $this->conexaoAtualiza = true;
        }

        $this->em->beginTransaction();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->em->rollback();
    }
}
