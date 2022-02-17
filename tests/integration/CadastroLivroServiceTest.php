<?php

use Core\Services\Emprestimo\{
    CadastroLivroService,
    CadastroAutorService,
};

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

    private function livroPersistido(Livro $l): Livro
    {
        return $this->persistidoById(Livro::class, $l->getId());
    }

    private function fixture($contexto)
    {
        $faker = Faker\Factory::create('pt_BR');

        switch($contexto){
        case 'ok':
            $autor = (new CadastroAutorService(
                $this->factory(AutoresRepository::class),
            ))->execute($faker->name());
            return [
                'titulo'        => $faker->name(),
                'idAutor'       => $autor->getId(),
                'quantidade'    => $faker->randomDigitNotNull(),
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
            titulo:     $fixture['titulo'],
            idAutor:    $fixture['idAutor'],
            quantidade: $fixture['quantidade'],
        );

        $this->assertNotNull($livro->getId());

        $persistido = $this->livroPersistido($livro);
        $this->assertEquals(
            $livro->getId(),
            $persistido->getId()
        );
        $this->assertEquals(
            $livro->titulo,
            $persistido->titulo
        );
        $this->assertEquals(
            $livro->autor->getId(),
            $persistido->autor->getId()
        );
        $this->assertEquals(
            $livro->quantidade,
            $persistido->quantidade
        );
    }
}
