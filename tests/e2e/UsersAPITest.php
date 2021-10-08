<?php

use Core\Models\Usuario;

/**
 * @testdox /api/users
 */
class UsersAPITest extends E2ETestCase
{
    private static $ep = '/api/users';

    public function testDeveCriarUsuario()
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

        $this->json('POST', self::$ep, $bodyRequest)
             ->seeJson($bodyResponse);

        $this->seeInDatabase('usuarios', ['email' => $bodyRequest['email']]);
    }
}
