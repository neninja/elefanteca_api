<?php

use Core\Services\Emprestimo\CadastroAutorService;
use Core\Models\Autor;

use App\Repositories\Doctrine\{
    AutoresRepository,
};

/**
 * @covers \Core\Services\Emprestimo\CadastroAutorService
 */
class CadastroAutorServiceTest extends IntegrationTestCase
{
    private function newSut()
    {
        return new CadastroAutorService(
            $this->factory(AutoresRepository::class),
        );
    }

    private function autorPersistido(Autor $a): Autor
    {
        return $this->persistidoById(Autor::class, $a->getId());
    }

    private function fixture($contexto)
    {
        $faker = Faker\Factory::create('pt_BR');

        switch($contexto){
        case 'ok':
            return [
                'nome' => $faker->name(),
            ];
        default:
            throw new \InvalidArgumentException();
        }
    }

    public function testPersisteComValoresObrigatorios()
    {
        $sut = $this->newSut();

        $fixture = $this->fixture('ok');

        $autor = $sut->execute(
            nome: $fixture['nome'],
        );

        $this->assertNotNull($autor->getId());

        $persistido = $this->autorPersistido($autor);
        $this->assertEquals(
            $autor->getId(),
            $persistido->getId()
        );
        $this->assertEquals(
            $autor->nome,
            $persistido->nome
        );
    }

}
