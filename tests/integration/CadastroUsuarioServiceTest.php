<?php

use Core\Services\Usuario\CadastroUsuarioService;
use Core\Models\Usuario;

use App\Repositories\Doctrine\{
    UsuariosRepository,
};
use App\Adapters\{
    LumenCryptProvider,
};

use Core\Exceptions\CoreException;

class CadastroUsuarioServiceTest extends IntegrationTestCase
{
    private function sut()
    {
        return new CadastroUsuarioService(
            $this->factory(UsuariosRepository::class),
            $this->factory(LumenCryptProvider::class),
        );
    }

    private function usuarioPersistido(Usuario $u): Usuario
    {
        return $this->persistidoById(Usuario::class, $u->getId());
    }

    private function fixture($contexto)
    {
        switch($contexto){
        case 'ok':
            return [
                'nome'  => $this->fakeName(),
                'cpf'   => $this->fakeCpf(),
                'email' => $this->fakeEmail(),
                'senha' => $this->fakePassword(),
            ];
        default:
            throw new \InvalidArgumentException();
        }
    }

    public function testPersisteComNomeCpfEmailSenhaCorretos()
    {
        $fixture = $this->fixture('ok');

        $usuario = $this->sut()->execute(
            nome:   $fixture['nome'],
            cpf:    $fixture['cpf'],
            email:  $fixture['email'],
            senha:  $fixture['senha'],
        );

        $this->assertNotNull($usuario->getId());

        $persistido = $this->usuarioPersistido($usuario);
        $this->assertEquals(
            $usuario->getId(),
            $persistido->getId()
        );
        $this->assertEquals(
            $usuario->cpf,
            $persistido->cpf
        );
        $this->assertEquals(
            $usuario->email,
            $persistido->email
        );
        $this->assertEquals(
            $usuario->email,
            $persistido->email
        );
        $this->assertEquals(
            $usuario->getSenha(),
            $persistido->getSenha()
        );
    }

    public function testCriptografaSenha()
    {
        $fixture = $this->fixture('ok');

        $usuario = $this->sut()->execute(
            nome:   $fixture['nome'],
            cpf:    $fixture['cpf'],
            email:  $fixture['email'],
            senha:  $fixture['senha'],
        );

        $this->assertNotEquals($fixture['senha'], $usuario->getSenha());
        $this->assertNotEquals(
            $fixture['senha'],
            $this->usuarioPersistido($usuario)->getSenha()
        );
    }

    public function testCriaComoAtivo()
    {
        $fixture = $this->fixture('ok');

        $usuario = $this->sut()->execute(
            nome:   $fixture['nome'],
            cpf:    $fixture['cpf'],
            email:  $fixture['email'],
            senha:  $fixture['senha'],
        );

        $this->assertTrue($usuario->getAtivo());

        $this->assertTrue($this->usuarioPersistido($usuario)->getAtivo());
    }

    public function testFalhaAoCriarComEmailExistente()
    {
        $fixture = $this->fixture('ok');

        $email = $fixture['email'];

        $this->sut()->execute(
            nome:   $fixture['nome'],
            cpf:    $fixture['cpf'],
            email:  $email,
            senha:  $fixture['senha'],
        );

        $this->expectException(CoreException::class);
        $this->expectExceptionMessage('Email já cadastrado');

        $fixture = $this->fixture('ok');

        $this->sut()->execute(
            nome:   $fixture['nome'].'2',
            cpf:    $fixture['cpf'],
            email:  $email,
            senha:  $fixture['senha'],
        );
    }

    public function testFalhaAoCriarComCpflExistente()
    {
        $fixture = $this->fixture('ok');

        $cpf = $fixture['cpf'];

        $this->sut()->execute(
            nome:   $fixture['nome'],
            cpf:    $cpf,
            email:  $fixture['email'],
            senha:  $fixture['senha'],
        );

        $this->expectException(CoreException::class);
        $this->expectExceptionMessage('CPF já cadastrado');

        $fixture = $this->fixture('ok');

        $this->sut()->execute(
            nome:   $fixture['nome'].'2',
            cpf:    $cpf,
            email:  $fixture['email'],
            senha:  $fixture['senha'],
        );
    }
}
