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

class CadastroUsuarioService
{
    function __construct(
        private IUsuariosRepository $repo,
        private ICriptografiaProvider $crypt,
    ){}

    public function execute(
        string $nome,
        string $cpf,
        string $senha,
        string $email,
        string $papel = 'membro',
    ): Usuario {

        $senhaCriptografada = $this->crypt->encrypt($senha);

        $u = new Usuario(
            nome:   $nome,
            cpf:    new CPF($cpf),
            senha:  $senhaCriptografada,
            email:  new Email($email),
        );

        return $this->repo->save($u);
    }
}
