<?php

use Core\Services\Emprestimo\CadastroAutorService;
use Core\Models\Autor;

use App\Repositories\Doctrine\{
    AutoresRepository,
};

class CadastroAutorServiceTest extends IntegrationTestCase
{
    private function sut()
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
        switch($contexto){
        case 'ok':
            return [
                'nome' => $this->fakeName(),
            ];
        default:
            throw new \InvalidArgumentException();
        }
    }

    public function testCriaComValoresObrigatorios()
    {
        $fixture = $this->fixture('ok');

        $autor = $this->sut()->execute(
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

    public function criaAutor()
    {
        $fixture = $this->fixture('ok');

        return $this->sut()->execute(
            nome: $fixture['nome'],
        );
    }

    public function testEditaComValoresObrigatorios()
    {
        $autor = $this->criaAutor();

        $nomeAlterado = $autor->nome."_alterado";

        $autor = $this->sut()->execute(
            id:     $autor->getId(),
            nome:   $nomeAlterado,
        );

        $persistido = $this->autorPersistido($autor);
        $this->assertEquals(
            $autor->getId(),
            $persistido->getId()
        );
        $this->assertEquals(
            $nomeAlterado,
            $persistido->nome
        );
    }
}
