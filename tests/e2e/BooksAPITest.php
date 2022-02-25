<?php

class BooksAPITest extends E2ETestCase
{
    private static $ep = '/api/books';

    protected function given(string $context, ...$params)
    {
        switch ($context) {
        case 'livro existente':
            return $this->criaLivroComAutor()[0];
        case 'livros existentes do mesmo autor':
            return $this->criaLivroComAutor($params[0]);
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

    protected function criaLivroComAutor(int $quantidadeLivros = 1)
    {
        $as = $this->factory(
            \Core\Services\Emprestimo\CadastroAutorService::class
        );

        $a = $as->execute($this->fakeName());

        $ls = $this->factory(
            \Core\Services\Emprestimo\CadastroLivroService::class
        );

        return array_map(function($i) use ($a, $ls) {
            return $ls->execute(
                titulo:     $this->fakeWords(),
                idAutor:    $a->getId(),
                quantidade: 1,
            );
        }, range(1, $quantidadeLivros));
    }

    /******** CREATE *******/

    public function testFalhaSemAutenticacaoAoCriar()
    {
        $autor = $this->given('autor existente');

        $req = [
            'title'      => $this->fakeWords(2),
            'idAutor'    => $autor->getId(),
            'quantidade' => 1,
        ];

        $this
            ->json('POST', self::$ep, $req)
            ->response
            ->assertUnauthorized();
    }

    public function testFalhaComoMembroAoCriar()
    {
        $autor = $this->given('autor existente');

        $req = [
            'title'      => $this->fakeWords(2),
            'idAutor'    => $autor->getId(),
            'quantidade' => 1,
        ];

        $this
            ->jsonMembro('POST', self::$ep, $req)
            ->response
            ->assertUnauthorized();
    }

    public function testCriaComoColaborador()
    {
        $autor = $this->given('autor existente');

        $req = [
            'title'     => $this->fakeWords(2),
            'author_id' => $autor->getId(),
            'amount'    => 1,
        ];

        $res = [
            'title'     => $req['title'],
            'amount'    => $req['amount'],
        ];

        $this
            ->jsonColaborador('POST', self::$ep, $req)
            ->seeJson($res)
            ->seeJsonStructure(['data' => ['id', 'author']])
            ->response
            ->assertOk();

        $this->seeInDatabase('livros', ['titulo' => $req['title']]);
    }

    public function testCriaComoAdmin()
    {
        $autor = $this->given('autor existente');

        $req = [
            'title'     => $this->fakeWords(2),
            'author_id' => $autor->getId(),
            'amount'    => 1,
        ];

        $res = [
            'title'     => $req['title'],
            'amount'    => $req['amount'],
        ];

        $this
            ->jsonColaborador('POST', self::$ep, $req)
            ->seeJson($res)
            ->seeJsonStructure(['data' => ['id', 'author']])
            ->response
            ->assertOk();

        $this->seeInDatabase('livros', ['titulo' => $req['title']]);
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
        $livros = $this->given('livros existentes do mesmo autor', 20);

        $data = array_chunk(
            array_map(fn($l) => [ 'title' => $l->titulo ], $livros), 10,
        );

        $p1 = $data[0];
        $p2 = $data[1];

        $response = $this
            ->jsonMembro('GET', self::$ep)
            ->seeJsonStructure(['data'])
            ->response
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
            ->assertJsonMissing($p2[9])
            ->assertOk();

        $this
            ->jsonMembro('GET', self::$ep.'?page=2')
            ->seeJsonStructure(['data'])
            ->response
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
            ->assertJsonMissing($p1[9])
            ->assertOk();
    }

    public function testListaPorTituloParcial()
    {
        $livros = $this->given('livros existentes do mesmo autor', 20);

        $data = array_map(fn($l) => [
            'title' => $l->titulo
        ], $livros);

        $titulo = $livros[3]->titulo;
        $titulo = substr($titulo, 1);
        $titulo = substr($titulo, 0, -1);

        $this
            ->jsonMembro('GET', self::$ep.'?title='.$titulo)
            ->seeJsonStructure(['data' => [['id',  'title', 'amount']]])
            ->response
            ->assertJsonMissing($data[0])
            ->assertJsonMissing($data[1])
            ->assertJsonMissing($data[2])
            ->assertJsonFragment($data[3])
            ->assertJsonMissing($data[4])
            ->assertOk();
    }

    public function testFalhaSemAutenticacaoAoListarPorId()
    {
        $l = $this->given('livro existente');

        $this
            ->json('GET', self::$ep."/{$l->getId()}")
            ->response
            ->assertUnauthorized();
    }

    public function testListaPorId()
    {
        $l = $this->given('livro existente');

        $this
            ->jsonMembro('GET', self::$ep."/{$l->getId()}")
            ->seeJsonStructure(['data' => ['id',  'title', 'amount']])
            ->response
            ->assertJsonFragment(['title' => $l->titulo])
            ->assertOk();
    }

    #/******** UPDATE *******/

    public function testFalhaSemAutenticacaoAoEditar()
    {
        $l = $this->given('livro existente');

        $req = [
            'title' => $l->titulo."diferente",
        ];

        $this
            ->json('PUT', self::$ep."/{$l->getId()}", $req)
            ->response
            ->assertUnauthorized();
    }

    public function testFalhaComoMembroAoEditar()
    {
        $l = $this->given('livro existente');

        $req = [
            'title' => $l->titulo."diferente",
        ];

        $this
            ->jsonMembro('PUT', self::$ep."/{$l->getId()}", $req)
            ->response
            ->assertUnauthorized();
    }

    public function testEditaComoColaborador()
    {
        $l = $this->given('livro existente');

        $req = [
            'title'     => $l->titulo."diferente",
            'author_id' => $l->autor->getId(),
            'amount'    => $l->quantidade,
        ];

        $this
            ->jsonColaborador('PUT', self::$ep."/{$l->getId()}", $req)
            ->seeJsonStructure(['data' => ['id',  'title', 'amount']])
            ->response
            ->assertJsonFragment(['title' => $req['title']])
            ->assertOk();

        $this->seeInDatabase('livros', [
            'id'     => $l->getId(),
            'titulo' => $req['title'],
        ]);
    }

    #/******** DELETE *******/

    public function testFalhaSemAutenticacaoAoDeletar()
    {
        $l = $this->given('livro existente');

        $this
            ->json('DELETE', self::$ep."/{$l->getId()}")
            ->response
            ->assertUnauthorized();
    }

    public function testDeletaComoColaborador()
    {
        $l = $this->given('livro existente');

        $this
            ->jsonColaborador('DELETE', self::$ep."/{$l->getId()}")
            ->response
            ->assertNoContent();

        $this->seeInDatabase('livros', [
            'id'    => $l->getId(),
            'ativo' => false,
        ]);
    }
}
