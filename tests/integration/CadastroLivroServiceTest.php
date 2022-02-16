<?php

use Core\Services\Emprestimo\CadastroLivroService;
use Core\Models\Livro;

use App\Repositories\Doctrine\{
    LivrosRepository,
    AutoresRepository,
};

/**
 * @covers \Core\Services\Emprestimo\CadastroLivroService
 */
class CadastroLivroServiceTest extends IntegrationTestCase
{
    private function newSut()
    {
        return new CadastroLivroService(
            $this->factory(LivrosRepository::class),
            $this->factory(AutoresRepository::class),
        );
    }

    private function usuarioPersistido(Usuario $u): Usuario
    {
        return $this->persistidoById(Usuario::class, $u->getId());
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

    public function testDevePersistirComValoresObrigatorios()
    {
        $sut = $this->newSut();

        $fixture = $this->fixture('ok');

        $livro = $sut->execute(
            titulo:     $fixture['nome'],
            idAutor:    $fixture['cpf'],
            quantidade: $fixture['email'],
        );

        $this->assertNotNull($livro->getId());

        $persistido = $this->livroPersistido($livro);
        $this->assertEquals(
            $livro->getId(),
            $persistido->getId()
        );
        $this->assertEquals(
            $usuario->titulo,
            $persistido->titulo
        );
        $this->assertEquals(
            $usuario->autor->getId(),
            $persistido->autor->getId()
        );
        $this->assertEquals(
            $usuario->quantidade,
            $persistido->quantidade
        );
    }

    /*
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
     */
}

