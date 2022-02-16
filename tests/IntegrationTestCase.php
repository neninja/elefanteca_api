<?php

use Core\Models\{
    Usuario,
    Livro,
    Autor,
};

abstract class IntegrationTestCase extends LumenTestCase
{
    private array $tables = [
        Usuario::class  => 'usuarios',
        Livro::class    => 'livros',
        Autor::class    => 'autores',
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
