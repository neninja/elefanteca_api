<?php

interface Core\Repositories;


use Core\Models\Usuario;

interface IUsuariosRepository
{
    public function save(Usuario $u): Usuario;
}
