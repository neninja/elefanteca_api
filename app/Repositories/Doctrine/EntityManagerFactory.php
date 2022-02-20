<?php

namespace App\Repositories\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;
use Doctrine\DBAL\Types\Type;

class EntityManagerFactory
{
    public function get(): EntityManagerInterface
    {
        $config = Setup::createXMLMetadataConfiguration(
            [__DIR__ . '/mapeamentos']
        );
        $con = [
            'driver'    => env('DB_CONNECTION_DOCTRINE'),
            'host'      => env('DB_HOST'), # nome no docker-compose.yml # db
            'dbname'    => env('DB_DATABASE'),
            'user'      => env('DB_USERNAME'),
            'password'  => env('DB_PASSWORD')
        ];

        $this->addCustomTypes();

        return EntityManager::create($con, $config);
    }

    private function addCustomTypes()
    {
        $types = [
            [ 'email', 'App\Repositories\Doctrine\Types\EmailType' ],
            [ 'cpf', 'App\Repositories\Doctrine\Types\CpfType' ],
            [ 'papel', 'App\Repositories\Doctrine\Types\PapelType' ],
        ];

        foreach($types as $type) {
            if(!Type::hasType($type[0])) {
                Type::addType($type[0], $type[1]);
            }
        }
    }
}
