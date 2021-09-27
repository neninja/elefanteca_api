<?php

namespace App\Repositories\Doctrine;

class EntityManagerCreator
{
    public function criaEntityManager(): EntityManagerInterface
    {
        var_dump(env('DB_USERNAME'));die;
        $config = Setup::createXMLMetadataConfiguration(
            [__DIR__ . '/../../mapeamentos']
        );
        $con = [
            'driver' => 'pdo_pgsql',
            'host' => 'db', # nome no docker-compose.yml
            'dbname' => 'ead_php_alura_doctrine-xml',
            'user' => 'root',
            'password' => '123'
        ];

        return EntityManager::create($con, $config);
    }
}
