<?php

class AuthorsAPITest extends E2ETestCase
{
    private static $ep = '/api/authors';

    protected function criaAutor()
    {
        $s = $this->factory(
            \Core\Services\Emprestimo\CadastroAutorService::class
        );

        $a = $s->execute($this->fakeName());

        $this->seeInDatabase('autores', [
            'id' => $a->getId(),
            'nome' => $a->nome
        ]);

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
            ->json('POST', self::$ep, ['name' => $this->fakeName()])
            ->response
            ->assertUnauthorized();
    }

    public function testCriaComoColaborador()
    {
        $body = [
            'name' => $this->fakeName(),
        ];

        $this
            ->jsonComoColaborador('POST', self::$ep, $body)
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
            ->jsonComoAdmin('POST', self::$ep, $body)
            ->seeJson($body)
            ->seeJsonStructure(['id'])
            ->response
            ->assertOk();

        $this->seeInDatabase('autores', ['nome' => $body['name']]);
    }

    /******** UPDATE *******/

    public function testFalhaSemAutenticacaoAoEditar()
    {
        $a = $this->criaAutor();

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
        $a = $this->criaAutor();

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
        $a = $this->criaAutor();

        $body = [
            'name' => $a->nome."diferente",
        ];

        $this
            ->jsonComoColaborador('PUT', self::$ep."/{$a->getId()}", $body)
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
