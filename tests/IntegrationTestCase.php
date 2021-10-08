<?php

use Core\Models\{
    Usuario,
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
