<?php

use Doctrine\ORM\EntityManagerInterface as EntityManager;

use Core\Models\{
    Usuario,
};

use App\Repositories\Doctrine\{
    UsuariosRepository,
};
use App\Adapters\{
    LumenCryptProvider,
};

abstract class IntegrationTestCase extends LumenTestCase
{
    private array $tables = [
        Usuario::class => 'usuarios'
    ];

    protected function persistidoById(
        string $namespace,
        int $id,
        string $column = 'id'
    ) {
        $this->seeInDatabase(
            $this->tables[$namespace],
            ['id' => $id]
        );
        return $this->databaseFindById($namespace, $id);
    }
}
