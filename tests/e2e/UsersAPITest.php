<?php

use Core\Models\Usuario;

class UsersAPITest extends E2ETestCase
{
    private static $ep = '/api/users';

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

    public function testCriaUsuario()
    {
        $faker = Faker\Factory::create('pt_BR');

        $bodyRequest = [
            'name'      => $faker->name(),
            'cpf'       => $faker->cpf(false),
            'password'  => $faker->password(),
            'email'     => $faker->email(),
        ];

        $bodyResponse = [
            'name'      => $bodyRequest['name'],
            'cpf'       => $bodyRequest['cpf'],
            'email'     => $bodyRequest['email'],
        ];

        $response = $this
            ->jsonComoColaborador('POST', self::$ep, $bodyRequest)
            ->seeStatusCode(200)
            ->seeJson($bodyResponse)
            ->response
            ->decodeResponseJson();

        $this->assertArrayHasKey('id', $response);

        $this->seeInDatabase('usuarios', ['email' => $bodyRequest['email']]);
    }
}
