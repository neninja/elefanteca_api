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
        $this->seeInDatabase('usuarios', ['id' => $u->getId()]);
        return $this->databaseFindById(Usuario::class, $u->getId());
    }

    private function fixture($contexto)
    {
        $faker = Faker\Factory::create('pt_BR');

        switch($contexto){
        case 'ok':
            return [
                'nome'  => $faker->name(),
                'cpf'   => $faker->cpf(false),
                'email' => $faker->email(),
                'senha' => $faker->password(),
            ];
        default:
            throw new \InvalidArgumentException();
        }
    }

    public function testDevePersistirComNomeCpfEmailSenhaCorretos()
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

    public function testDeveCriptografarSenha()
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

    public function testDeveCriarAtivo()
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
