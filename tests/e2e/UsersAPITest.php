<?php

class UsersAPITest extends E2ETestCase
{
    private static $ep = '/api/users';

    public function testFalhaSemAutenticacao()
    {
        $bodyRequest = [
            'name' => $this->fakeName(),
        ];

        $this
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

        $this
            ->jsonComoColaborador('POST', self::$ep, $bodyRequest)
            ->seeJson($bodyResponse)
            ->seeJsonStructure(['id'])
            ->seeStatusCode(200);

        $this->seeInDatabase('usuarios', ['email' => $bodyRequest['email']]);
    }
}
