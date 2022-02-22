<?php

class UsersAPITest extends E2ETestCase
{
    private static $ep = '/api/users';

    /**
     * @testdox Falha $_dataName
     * @dataProvider bodyInvalido
     */
    public function testValida($body, $camposComErro)
    {
        $this
            ->call('POST', self::$ep, $body)
            ->assertJsonValidationErrors($camposComErro, $responseKey = null);
    }

    public function bodyInvalido()
    {
        yield "sem todos campos" => [
            [],
            ['name','cpf','email', 'password'],
        ];
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
            ->json('POST', self::$ep, $bodyRequest)
            ->seeJson($bodyResponse)
            ->seeJsonStructure(['id'])
            ->response
            ->assertOk();

        $this->seeInDatabase('usuarios', ['email' => $bodyRequest['email']]);
    }

    public function testCriaUsuarioComoColaborador()
    {
        $bodyRequest = [
            'name'      => $this->fakeName(),
            'cpf'       => $this->fakeCpf(),
            'password'  => $this->fakePassword(),
            'email'     => $this->fakeEmail(),
            'role'      => 'colaborador',
        ];

        $bodyResponse = [
            'name'      => $bodyRequest['name'],
            'cpf'       => $bodyRequest['cpf'],
            'email'     => $bodyRequest['email'],
            'role'      => $bodyRequest['role'],
        ];

        $this
            ->jsonComoAdmin('POST', self::$ep, $bodyRequest)
            ->seeJson($bodyResponse)
            ->seeJsonStructure(['id'])
            ->response
            ->assertOk();

        $this->seeInDatabase('usuarios', ['email' => $bodyRequest['email']]);
    }

    public function testCriaUsuarioComoMembroTentandoCriarColaborador()
    {
        $bodyRequest = [
            'name'      => $this->fakeName(),
            'cpf'       => $this->fakeCpf(),
            'password'  => $this->fakePassword(),
            'email'     => $this->fakeEmail(),
            'role'      => 'colaborador',
        ];

        $bodyResponse = [
            'name'      => $bodyRequest['name'],
            'cpf'       => $bodyRequest['cpf'],
            'email'     => $bodyRequest['email'],
            'role'      => 'membro',
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
