<?php

namespace Core\Services\Usuario;

use Core\Models\{
    Usuario,
    CPF,
    Email,
};

use Core\Repositories\{
    IUsuariosRepository,
};


use Core\Providers\{
    ICriptografiaProvider,
};

class LoginEmailSenhaService
{
    function __construct(
        private IUsuariosRepository $repo,
        private ICriptografiaProvider $crypt,
    ){}

    public function execute(
        string $email,
        string $senha,
    ): bool {
        $usuario = $this->repo->findByEmail($email);

        if(empty($usuario)) {
            return false;
        }

        $senhaDescriptografada = $this->crypt->decrypt($usuario->getSenha());
        return $senhaDescriptografada === $senha;
    }
}

