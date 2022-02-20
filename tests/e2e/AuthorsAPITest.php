<?php

class AuthorsAPITest extends E2ETestCase
{
    private static $ep = '/api/authors';

    public function testFalhaSemAutenticacao()
    {
        $bodyRequest = [
            'name' => $this->fakeName(),
        ];

        $response = $this
            ->json('POST', self::$ep, $bodyRequest)
            ->seeStatusCode(401);
    }

    public function testFalhaComoMembro()
    {
        $bodyRequest = [
            'name' => $this->fakeName(),
        ];

        $response = $this
            ->json('POST', self::$ep, $bodyRequest)
            ->seeStatusCode(401);
    }

    public function testCriaAutorComoColaborador()
    {
        $bodyRequest = [
            'name' => $this->fakeName(),
        ];

        $bodyResponse = [
            'name'  => $bodyRequest['name'],
        ];

        $response = $this
            ->jsonComoColaborador('POST', self::$ep, $bodyRequest)
            ->seeJson($bodyResponse)
            ->seeJsonStructure(['id'])
            ->seeStatusCode(200)
            ->response
            ->decodeResponseJson();

        $this->assertArrayHasKey('id', $response);

        $this->seeInDatabase('autores', ['nome' => $bodyRequest['name']]);
    }

    public function testCriaAutorComoAdmin()
    {
        $bodyRequest = [
            'name' => $this->fakeName(),
        ];

        $bodyResponse = [
            'name'  => $bodyRequest['name'],
        ];

        $response = $this
            ->jsonComoAdmin('POST', self::$ep, $bodyRequest)
            ->seeJson($bodyResponse)
            ->seeJsonStructure(['id'])
            ->seeStatusCode(200)
            ->response
            ->decodeResponseJson();

        $this->assertArrayHasKey('id', $response);

        $this->seeInDatabase('autores', ['nome' => $bodyRequest['name']]);
    }
}
