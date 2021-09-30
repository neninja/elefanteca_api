<?php

namespace App\Repositories\Doctrine;

use Core\Models\Usuario;

class UsuariosRepository implements \Core\Repositories\IUsuariosRepository
{
    private $em;

    function __construct()
    {
        $this->em = (new EntityManagerFactory())->get();
    }

    public function save(Usuario $u): Usuario
    {
        $this->em->persist($u);
        $this->em->flush();
        return $u;
    }
}
