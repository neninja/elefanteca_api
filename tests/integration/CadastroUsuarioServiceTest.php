<?php

use Core\Services\Usuario\CadastroUsuarioService;

class CadastroUsuarioServiceTest extends TestCase
{
    private function newSut()
    {
        $repo = (new \App\Repositories\Doctrine\UsuariosRepository());
        return new CadastroUsuarioService();
    }

    private function fixture($contexto)
    {
        $t = time();
        switch($contexto){
        case 'ok':
            return [
                'nome'  => "nome$t",
                'cpf'   => "64834139042",
                'email' => "fake$t@mail.com",
                'senha' => "senha$t",
            ];
        default:
            throw new \InvalidArgumentException();
        }
    }

    public function testDeveCadastrarComNomeCpfEmailSenhaCorretos()
    {
        $sut = $this->newSut();

        $fixture = $this->fixture('ok');

        $usuario = $sut(
            nome:   $fixture['nome'],
            cpf:    $fixture['cpf'],
            email:  $fixture['email'],
            senha:  $fixture['senha'],
        );

        $this->assertNotNull($usuario->getId());
    }

    public function testDeveCriptografarSenha()
    {
        $sut = $this->newSut();

        $fixture = $this->fixture('ok');

        $usuario = $sut(
            nome:   $fixture['nome'],
            cpf:    $fixture['cpf'],
            email:  $fixture['email'],
            senha:  $fixture['senha'],
        );

        $this->assertNotEquals($fixture['senha'], $usuario->getSenha());
    }

    public function testDeveCriarAtivo()
    {
        $sut = $this->newSut();

        $fixture = $this->fixture('ok');

        $usuario = $sut(
            nome:   $fixture['nome'],
            cpf:    $fixture['cpf'],
            email:  $fixture['email'],
            senha:  $fixture['senha'],
        );

        $this->assertTrue($usuario->getAtivo());
    }
}
