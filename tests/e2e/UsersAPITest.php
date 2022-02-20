<?php

class UsersAPITest extends E2ETestCase
{
    private static $ep = '/api/users';

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

        $this
            ->json('POST', self::$ep, $bodyRequest)
            ->seeJson($bodyResponse)
            ->seeJsonStructure(['id'])
            ->response
            ->assertOk();

        $this->seeInDatabase('usuarios', ['email' => $bodyRequest['email']]);
    }
}
