<?php

class AuthorsAPITest extends E2ETestCase
{
    private static $ep = '/api/authors';

    protected function criaAutor()
    {
        $s = $this->factory(
            \Core\Services\Emprestimo\CadastroAutorService::class
        );

        $u = $s->execute($this->fakeName());

        $this->seeInDatabase('autores', [
            'id' => $u->getId(),
            'nome' => $u->nome
        ]);

        return $u;
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
        $u = $this->criaAutor();

        $body = [
            'name' => $u->nome."diferente",
        ];

        $this
            ->json('PUT', self::$ep."/{$u->getId()}", $body)
            ->response
            ->assertUnauthorized();
    }

    public function testFalhaComoMembroAoEditar()
    {
        $u = $this->criaAutor();

        $body = [
            'name' => $u->nome."diferente",
        ];

        $this
            ->json('PUT', self::$ep."/{$u->getId()}", $body)
            ->response
            ->assertUnauthorized();
    }

    public function testEditaComoColaborador()
    {
        $u = $this->criaAutor();

        $body = [
            'name' => $u->nome."diferente",
        ];

        $this
            ->jsonComoColaborador('PUT', self::$ep."/{$u->getId()}", $body)
            ->seeJson($body)
            ->seeJsonStructure(['id'])
            ->response
            ->assertOk();

        $this->seeInDatabase('autores', [
            'id'    => $u->getId(),
            'nome'  => $body['name'],
        ]);
    }
}
