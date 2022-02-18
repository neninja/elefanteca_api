<?php

class AuthorsAPITest extends E2ETestCase
{
    private static $ep = '/api/authors';

    public function testFalhaSemAutenticacao()
    {
        $faker = Faker\Factory::create('pt_BR');

        $bodyRequest = [
            'name' => $faker->name(),
        ];

        $response = $this
            ->json('POST', self::$ep, $bodyRequest)
            ->seeStatusCode(401);
    }

    public function testCriaAutorComoColaborador()
    {
        $faker = Faker\Factory::create('pt_BR');

        $bodyRequest = [
            'name' => $faker->name(),
        ];

        $bodyResponse = [
            'name'  => $bodyRequest['name'],
        ];


        $response = $this
            ->jsonComoColaborador('POST', self::$ep, $bodyRequest)
            ->seeStatusCode(200)
            ->seeJson($bodyResponse)
            ->seeJsonStructure(['id'])
            ->response
            ->decodeResponseJson();

        $this->assertArrayHasKey('id', $response);

        $this->seeInDatabase('autores', ['nome' => $bodyRequest['name']]);
    }
}
