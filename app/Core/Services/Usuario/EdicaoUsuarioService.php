<?php

namespace Core\Services\Usuario;

use Core\Models\{
    Usuario,
    CPF,
    Email,
    Papel,
};

use Core\Repositories\{
    IUsuariosRepository,
};


use Core\Exceptions\ValidationException;

class EdicaoUsuarioService
{
    function __construct(
        private IUsuariosRepository $repo,
    ){}

    public function execute(
        int    $id,
        string $nome,
        string $cpf,
        string $email,
        string $papel = '',
    ): Usuario {
        if(empty($papel)) {
            $papel = Papel::$MEMBRO;
        }

        $u = $this->repo->findById($id) ;

        if(is_null($u)) {
            throw new ValidationException("Id inexistente", $id);
        }

        if($email !== $u->email->getEmail()) {
            $emailEncontrado = $this->repo->findByEmail($email);

            if(!is_null($emailEncontrado)) {
                throw new ValidationException("Email já cadastrado", $email);
            }
        }

        if($cpf !== $u->cpf->getNumero()) {
            $cpfEncontrado = $this->repo->findByCpf($cpf) ;
            if(!is_null($cpfEncontrado)) {
                throw new ValidationException("Cpf já cadastrado", $cpf);
            }
        }

        $u->nome  = $nome;
        $u->cpf   = new CPF($cpf);
        $u->email = new Email($email);
        $u->papel = new Papel($papel);

        return $this->repo->save($u);
    }
}
