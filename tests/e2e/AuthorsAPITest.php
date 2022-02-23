<?php

class AuthorsAPITest extends E2ETestCase
{
    private static $ep = '/api/authors';

    protected function given(string $context, ...$params)
    {
        switch ($context) {
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
            ->jsonMembro('GET', self::$ep.'?page=1')
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
        $this->markTestIncomplete();
    }

    public function testListaPorId()
    {
        $this->markTestIncomplete();
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
}
