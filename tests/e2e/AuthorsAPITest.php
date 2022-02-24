<?php

class AuthorsAPITest extends E2ETestCase
{
    private static $ep = '/api/authors';

    protected function given(string $context, ...$params)
    {
        switch ($context) {
        case 'autor existente com livro':
            return $this->criaAutorComLivro();
        case 'autor existente':
            return $this->criaAutor();
        case 'autores existentes':
            return array_map(
                fn() => $this->criaAutor(), range(1, $params[0])
            );
        }
    }

    protected function criaAutor()
    {
        $s = $this->factory(
            \Core\Services\Emprestimo\CadastroAutorService::class
        );

        $a = $s->execute($this->fakeName());

        return $a;
    }

    protected function criaAutorComLivro()
    {
        $as = $this->factory(
            \Core\Services\Emprestimo\CadastroAutorService::class
        );

        $a = $as->execute($this->fakeName());

        $ls = $this->factory(
            \Core\Services\Emprestimo\CadastroLivroService::class
        );

        $ls->execute(
            titulo:     $this->fakeWord(),
            idAutor:    $a->getId(),
            quantidade: 1,
        );

        return $a;
    }

    /******** CREATE *******/

    public function testFalhaSemAutenticacaoAoCriar()
    {
        $this
            ->json('POST', self::$ep, ['name' => $this->fakeName()])
            ->response
            ->assertUnauthorized();
    }

    public function testFalhaComoMembroAoCriar()
    {
        $this
            ->jsonMembro('POST', self::$ep, ['name' => $this->fakeName()])
            ->response
            ->assertUnauthorized();
    }

    public function testCriaComoColaborador()
    {
        $body = [
            'name' => $this->fakeName(),
        ];

        $this
            ->jsonColaborador('POST', self::$ep, $body)
            ->seeJson($body)
            ->seeJsonStructure(['id'])
            ->response
            ->assertOk();

        $this->seeInDatabase('autores', ['nome' => $body['name']]);
    }

    public function testCriaComoAdmin()
    {
        $body = [
            'name' => $this->fakeName(),
        ];

        $this
            ->jsonAdmin('POST', self::$ep, $body)
            ->seeJson($body)
            ->seeJsonStructure(['id'])
            ->response
            ->assertOk();

        $this->seeInDatabase('autores', ['nome' => $body['name']]);
    }

    /******** READ *******/

    public function testFalhaSemAutenticacaoAoListar()
    {
        $this
            ->json('GET', self::$ep)
            ->response
            ->assertUnauthorized();
    }

    public function testLista20Com2Paginas()
    {
        $autores = $this->given('autores existentes', 20);

        $data = array_chunk(
            array_map(fn($a) => [ 'nome' => $a->nome ], $autores), 10
        );

        $p1 = ['data' => $data[0]];
        $p2 = ['data' => $data[1]];

        $this
            ->jsonMembro('GET', self::$ep)
            ->seeJsonEquals($p1)
            ->response
            ->assertOk();

        $this
            ->jsonMembro('GET', self::$ep.'?page=2')
            ->seeJsonEquals($p2)
            ->response
            ->assertOk();
    }

    public function testListaPorNomeParcial()
    {
        $autores = $this->given('autores existentes', 5);

        $nome = $autores[3]->nome;
        $nome = substr($nome, 1);
        $nome = substr($nome, 0, -1);

        $r = [
            'data' => [$autores[3]]
        ];

        $this
            ->jsonMembro('GET', self::$ep.'?name='.$nome)
            ->seeJsonEquals($r)
            ->response
            ->assertOk();
    }

    public function testFalhaSemAutenticacaoAoListarPorId()
    {
        $a = $this->given('autor existente');

        $this
            ->json('GET', self::$ep."/{$a->getId()}")
            ->response
            ->assertUnauthorized();
    }

    public function testListaPorId()
    {
        $a = $this->given('autor existente');

        $r = [ 'data' => $a ];

        $this
            ->jsonMembro('GET', self::$ep."/{$a->getId()}")
            ->seeJsonEquals($r)
            ->response
            ->assertOk();
    }

    /******** UPDATE *******/

    public function testFalhaSemAutenticacaoAoEditar()
    {
        $a = $this->given('autor existente');

        $body = [
            'name' => $a->nome."diferente",
        ];

        $this
            ->json('PUT', self::$ep."/{$a->getId()}", $body)
            ->response
            ->assertUnauthorized();
    }

    public function testFalhaComoMembroAoEditar()
    {
        $a = $this->given('autor existente');

        $body = [
            'name' => $a->nome."diferente",
        ];

        $this
            ->json('PUT', self::$ep."/{$a->getId()}", $body)
            ->response
            ->assertUnauthorized();
    }

    public function testEditaComoColaborador()
    {
        $a = $this->given('autor existente');

        $body = [
            'name' => $a->nome."diferente",
        ];

        $this
            ->jsonColaborador('PUT', self::$ep."/{$a->getId()}", $body)
            ->seeJson($body)
            ->seeJsonStructure(['id'])
            ->response
            ->assertOk();

        $this->seeInDatabase('autores', [
            'id'    => $a->getId(),
            'nome'  => $body['name'],
        ]);
    }

    /******** DELETE *******/

    public function testFalhaSemAutenticacaoAoDeletar()
    {
        $a = $this->given('autor existente');

        $this
            ->json('DELETE', self::$ep."/{$a->getId()}")
            ->response
            ->assertUnauthorized();
    }

    public function testDeletaComoColaborador()
    {
        $a = $this->given('autor existente com livro');

        $this
            ->jsonColaborador('DELETE', self::$ep."/{$a->getId()}")
            ->response
            ->assertNoContent();

        $this->notSeeInDatabase('autores', [
            'id'    => $a->getId(),
        ]);
    }
}
