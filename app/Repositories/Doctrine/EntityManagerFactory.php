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
            [ 'email', 'App\Repositories\Doctrine\Types\EmailType' ]
        ];

        $types = array_filter($types, fn($t) => !Type::hasType("email"));
        foreach($types as $type) {
            Type::addType($type[0], $type[1]);
        }

        // Type::addType('email', 'App\Repositories\Doctrine\Types\EmailType');
    }
}
