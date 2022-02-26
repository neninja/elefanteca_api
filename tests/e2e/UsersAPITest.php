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
        $req = [
            'name'      => $this->fakeName(),
            'cpf'       => $this->fakeCpf(),
            'password'  => $this->fakePassword(),
            'email'     => $this->fakeEmail(),
        ];

        $res = [
            'name'      => $req['name'],
            'cpf'       => $req['cpf'],
            'email'     => $req['email'],
        ];

        $this
            ->json('POST', self::$ep, $req)
            ->seeJson($res)
            ->seeJsonStructure(['data' => ['id']])
            ->response
            ->assertOk();

        $this->seeInDatabase('usuarios', ['email' => $req['email']]);
    }

    public function testCriaUsuarioComoColaborador()
    {
        $req = [
            'name'      => $this->fakeName(),
            'cpf'       => $this->fakeCpf(),
            'password'  => $this->fakePassword(),
            'email'     => $this->fakeEmail(),
            'role'      => 'colaborador',
        ];

        $res = [
            'name'      => $req['name'],
            'cpf'       => $req['cpf'],
            'email'     => $req['email'],
            'role'      => $req['role'],
        ];

        $this
            ->jsonAdmin('POST', self::$ep, $req)
            ->seeJson($res)
            ->seeJsonStructure(['data' => ['id']])
            ->response
            ->assertOk();

        $this->seeInDatabase('usuarios', ['email' => $req['email']]);
    }

    public function testCriaUsuarioComoMembroTentandoCriarColaborador()
    {
        $req = [
            'name'      => $this->fakeName(),
            'cpf'       => $this->fakeCpf(),
            'password'  => $this->fakePassword(),
            'email'     => $this->fakeEmail(),
            'role'      => 'colaborador',
        ];

        $res = [
            'name'      => $req['name'],
            'cpf'       => $req['cpf'],
            'email'     => $req['email'],
            'role'      => 'membro',
        ];

        $this
            ->json('POST', self::$ep, $req)
            ->seeJson($res)
            ->seeJsonStructure(['data' => ['id']])
            ->response
            ->assertOk();

        $this->seeInDatabase('usuarios', ['email' => $req['email']]);
    }
}
