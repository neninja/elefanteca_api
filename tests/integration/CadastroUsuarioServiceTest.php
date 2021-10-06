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
        return $this->databaseFindById(Usuario::class, $u->getId());
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

    public function testDeveCriptografarSenha()
    {
        $sut = $this->newSut();

        $fixture = $this->fixture('ok');

        $usuario = $sut->execute(
            nome:   $fixture['nome'],
            cpf:    $fixture['cpf'],
            email:  $fixture['email'],
            senha:  'aaaa',
        );

        $this->assertNotEquals($fixture['senha'], $usuario->getSenha());
        $this->assertNotEquals(
            $fixture['senha'],
            $this->usuarioPersistido($usuario)->getSenha()
        );
    }

    public function testDeveCriarAtivo()
    {
        $sut = $this->newSut();

        $fixture = $this->fixture('ok');

        $usuario = $sut->execute(
            nome:   $fixture['nome'],
            cpf:    $fixture['cpf'],
            email:  $fixture['email'],
            senha:  'bbbbbbb',
        );

        $this->assertTrue($usuario->getAtivo());

        $this->assertTrue($this->usuarioPersistido($usuario)->getAtivo());
    }

    public function testDeveCadastrarComNomeCpfEmailSenhaCorretos()
    {
        $sut = $this->newSut();

        $fixture = $this->fixture('ok');

        $usuario = $sut->execute(
            nome:   $fixture['nome'],
            cpf:    $fixture['cpf'],
            email:  $fixture['email'],
            senha:  'senhadandoruim',
        );

        $this->assertNotNull($usuario->getId());

        $persistido = $this->usuarioPersistido($usuario);
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
        // var_dump([
                // 'senha do banco' => $this->databaseQuery('select * from usuarios')[0]['senha'],
                // 'usuario' => $usuario->getSenha(),
                // 'persistido' => $persistido->getSenha()
            // ]);
        $this->assertEquals(
            $usuario->getSenha(),
            $persistido->getSenha()
        );
    }
}
