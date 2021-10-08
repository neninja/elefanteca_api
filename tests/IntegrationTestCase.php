<?php

use Doctrine\ORM\EntityManagerInterface as EntityManager;

use App\Repositories\Doctrine\{
    UsuariosRepository,
};
use App\Adapters\{
    LumenCryptProvider,
};

abstract class IntegrationTestCase extends LumenTestCase
{
}
