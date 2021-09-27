<?php

namespace App\Repositories\Doctrine;

use Core\Models\Usuario;

class UsuariosRepository implements \Core\Repositories\IUsuariosRepository
{
    private $em;

    function __construct()
    {
        $this->em = (new EntityManagerCreator())->criaEntityManager();
    }

    public function save(Usuario $u): Usuario
    {
        return $u;
    }
}
