<?php

use Core\Models\Usuario;

class UsersTest extends E2ETestCase
{
    private $ep = '/api/users';

    public function testDeveCriarUsuario()
    {
        $bodyRequest = [
            'name'      => 'Sally',
            'cpf'       => '17427351002',
            'password'  => 'djiajdij34214',
            'email'     => 'sally@foo.com',
        ];

        $bodyResponse = [
            'name'      => 'Sally',
            'cpf'       => '17427351002',
            'email'     => 'sally@foo.com',
        ];

        $this->json('POST', $this->ep, $bodyRequest)
             ->seeJson($bodyResponse);

        $this->seeInDatabase('usuarios', ['email' => 'sally@foo.com']);
    }
}
