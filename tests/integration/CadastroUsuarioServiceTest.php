<?php

use Core\Services\Usuario\CadastroUsuarioService;
use Core\Models\Usuario;

use App\Repositories\Doctrine\{
    UsuariosRepository,
};
use App\Adapters\{
    LumenCryptProvider,
};

/**
 * @covers \Core\Services\Usuario\CadastroUsuarioService
 */
class CadastroUsuarioServiceTest extends IntegrationTestCase
{
    private function newSut()
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
        $sut = $this->newSut();

        $fixture = $this->fixture('ok');

        $usuario = $sut->execute(
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
        $sut = $this->newSut();

        $fixture = $this->fixture('ok');

        $usuario = $sut->execute(
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
        $sut = $this->newSut();

        $fixture = $this->fixture('ok');

        $usuario = $sut->execute(
            nome:   $fixture['nome'],
            cpf:    $fixture['cpf'],
            email:  $fixture['email'],
            senha:  $fixture['senha'],
        );

        $this->assertTrue($usuario->getAtivo());

        $this->assertTrue($this->usuarioPersistido($usuario)->getAtivo());
    }
}
