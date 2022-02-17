<?php

use Core\Models\Usuario;

class AuthorsAPITest extends E2ETestCase
{
    private static $ep = '/api/authors';

    public function testDeveCriarAutor()
    {
        $faker = Faker\Factory::create('pt_BR');

        $bodyRequest = [
            'name' => $faker->name(),
        ];

        $bodyResponse = [
            'name'  => $bodyRequest['name'],
        ];

        $response = $this->json('POST', self::$ep, $bodyRequest)
                         ->seeJson($bodyResponse)
                         ->response
                         ->decodeResponseJson();

        $this->assertArrayHasKey('id', $response);

        $this->seeInDatabase('autores', ['nome' => $bodyRequest['name']]);
    }
}
