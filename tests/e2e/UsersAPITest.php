<?php

use Core\Models\Usuario;

class UsersAPITest extends E2ETestCase
{
    private static $ep = '/api/users';

    public function testFalhaSemAutenticacao()
    {
        $bodyRequest = [
            'name' => $this->fakeName(),
        ];

        $response = $this
            ->json('POST', self::$ep, $bodyRequest)
            ->seeStatusCode(401);
    }

    public function testCriaUsuario()
    {
        $bodyRequest = [
            'name'      => $this->fakeName(),
            'cpf'       => $this->fakeCpf(),
            'password'  => $this->fakePassword(),
            'email'     => $this->fakeEmail(),
        ];

        $bodyResponse = [
            'name'      => $bodyRequest['name'],
            'cpf'       => $bodyRequest['cpf'],
            'email'     => $bodyRequest['email'],
        ];

        $response = $this
            ->jsonComoColaborador('POST', self::$ep, $bodyRequest)
            ->seeJson($bodyResponse)
            ->seeStatusCode(200)
            ->response
            ->decodeResponseJson();

        $this->assertArrayHasKey('id', $response);

        $this->seeInDatabase('usuarios', ['email' => $bodyRequest['email']]);
    }
}
