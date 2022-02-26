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

        return $s->execute($this->fakeName());
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
            ->json('POST', self::$ep)
            ->response
            ->assertUnauthorized();
    }

    public function testFalhaComoMembroAoCriar()
    {
        $this
            ->jsonMembro('POST', self::$ep)
            ->response
            ->assertUnauthorized();
    }

    public function testCriaComoColaborador()
    {
        $req = [
            'name' => $this->fakeName(),
        ];

        $this
            ->jsonColaborador('POST', self::$ep, $req)
            ->seeJson($req)
            ->seeJsonStructure(['data' => ['id']])
            ->response
            ->assertOk();

        $this->seeInDatabase('autores', ['nome' => $req['name']]);
    }

    public function testCriaComoAdmin()
    {
        $req = [
            'name' => $this->fakeName(),
        ];

        $this
            ->jsonAdmin('POST', self::$ep, $req)
            ->seeJson($req)
            ->seeJsonStructure(['data' => ['id']])
            ->response
            ->assertOk();

        $this->seeInDatabase('autores', ['nome' => $req['name']]);
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
            array_map(fn($a) => [ 'name' => $a->nome ], $autores), 10,
        );

        $p1 = $data[0];
        $p2 = $data[1];

        $this
            ->jsonMembro('GET', self::$ep)
            ->seeJsonStructure(['data'])
            ->response
            ->assertOk()
            ->assertJsonCount(10, 'data')
            ->assertJsonFragment($p1[0])
            ->assertJsonFragment($p1[1])
            ->assertJsonFragment($p1[2])
            ->assertJsonFragment($p1[3])
            ->assertJsonFragment($p1[4])
            ->assertJsonFragment($p1[5])
            ->assertJsonFragment($p1[6])
            ->assertJsonFragment($p1[7])
            ->assertJsonFragment($p1[8])
            ->assertJsonFragment($p1[9])
            ->assertJsonMissing($p2[0])
            ->assertJsonMissing($p2[1])
            ->assertJsonMissing($p2[2])
            ->assertJsonMissing($p2[3])
            ->assertJsonMissing($p2[4])
            ->assertJsonMissing($p2[5])
            ->assertJsonMissing($p2[6])
            ->assertJsonMissing($p2[7])
            ->assertJsonMissing($p2[8])
            ->assertJsonMissing($p2[9]);

        $this
            ->jsonMembro('GET', self::$ep.'?page=2')
            ->seeJsonStructure(['data'])
            ->response
            ->assertOk()
            ->assertJsonCount(10, 'data')
            ->assertJsonFragment($p2[0])
            ->assertJsonFragment($p2[1])
            ->assertJsonFragment($p2[2])
            ->assertJsonFragment($p2[3])
            ->assertJsonFragment($p2[4])
            ->assertJsonFragment($p2[5])
            ->assertJsonFragment($p2[6])
            ->assertJsonFragment($p2[7])
            ->assertJsonFragment($p2[8])
            ->assertJsonFragment($p2[9])
            ->assertJsonMissing($p1[0])
            ->assertJsonMissing($p1[1])
            ->assertJsonMissing($p1[2])
            ->assertJsonMissing($p1[3])
            ->assertJsonMissing($p1[4])
            ->assertJsonMissing($p1[5])
            ->assertJsonMissing($p1[6])
            ->assertJsonMissing($p1[7])
            ->assertJsonMissing($p1[8])
            ->assertJsonMissing($p1[9]);
    }

    public function testListaPorNomeParcial()
    {
        $autores = $this->given('autores existentes', 5);

        $data = array_map(fn($a) => [
            'name' => $a->nome
        ], $autores);

        $nome = $autores[3]->nome;
        $nome = substr($nome, 1);
        $nome = substr($nome, 0, -1);

        $this
            ->jsonMembro('GET', self::$ep.'?name='.$nome)
            ->seeJsonStructure(['data' => [['id',  'name']]])
            ->response
            ->assertOk()
            ->assertJsonMissing($data[0])
            ->assertJsonMissing($data[1])
            ->assertJsonMissing($data[2])
            ->assertJsonFragment($data[3])
            ->assertJsonMissing($data[4]);
    }

    public function testFalhaSemAutenticacaoAoListarPorId()
    {
        $this
            ->json('GET', self::$ep."/123456")
            ->response
            ->assertUnauthorized();
    }

    public function testListaPorId()
    {
        $a = $this->given('autor existente');

        $this
            ->jsonMembro('GET', self::$ep."/{$a->getId()}")
            ->seeJsonStructure(['data' => ['id',  'name']])
            ->response
            ->assertOk()
            ->assertJsonFragment(['name' => $a->nome]);
    }

    public function testFalhaSeNaoExisteAoListarPorId()
    {
        $this
            ->jsonMembro('GET', self::$ep.'/123456')
            ->response
            ->assertNotFound();
    }

    /******** UPDATE *******/

    public function testFalhaSemAutenticacaoAoEditar()
    {
        $this
            ->json('PUT', self::$ep."/123456")
            ->response
            ->assertUnauthorized();
    }

    public function testFalhaComoMembroAoEditar()
    {
        $this
            ->jsonMembro('PUT', self::$ep."/123456")
            ->response
            ->assertUnauthorized();
    }

    public function testEditaComoColaborador()
    {
        $a = $this->given('autor existente');

        $req = [
            'name' => $a->nome."diferente",
        ];

        $this
            ->jsonColaborador('PUT', self::$ep."/{$a->getId()}", $req)
            ->seeJsonStructure(['data' => ['id',  'name']])
            ->response
            ->assertOk()
            ->assertJsonFragment(['name' => $req['name']]);

        $this->seeInDatabase('autores', [
            'id'    => $a->getId(),
            'nome'  => $req['name'],
        ]);
    }

    public function testFalhaSeNaoExisteAoEditarPorId()
    {
        $this
            ->jsonColaborador('PUT', self::$ep.'/123456')
            ->response
            ->assertNotFound();
    }

    /******** DELETE *******/

    public function testFalhaSemAutenticacaoAoDeletar()
    {
        $this
            ->json('DELETE', self::$ep."/123456")
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
            'id' => $a->getId(),
        ]);
    }

    public function testFalhaSeNaoExisteAoDeletar()
    {
        $this->markTestIncomplete();
        $this
            ->jsonColaborador('DELETE', self::$ep.'/123456')
            ->response
            ->assertNotFound();
    }
}
