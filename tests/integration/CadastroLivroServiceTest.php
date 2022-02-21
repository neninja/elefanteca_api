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

class CadastroLivroServiceTest extends IntegrationTestCase
{
    private function sut()
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
        switch($contexto){
        case 'ok':
            $autor = $this->factory(CadastroAutorService::class)
                          ->execute($this->fakeName());
            return [
                'titulo'        => $this->fakeName(),
                'idAutor'       => $autor->getId(),
                'quantidade'    => $this->fakeDigit(),
            ];
        default:
            throw new \InvalidArgumentException();
        }
    }

    public function testPersisteComValoresObrigatorios()
    {
        $fixture = $this->fixture('ok');

        $livro = $this->sut()->execute(
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
