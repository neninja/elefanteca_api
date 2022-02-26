<?php

class UsersAPITest extends E2ETestCase
{
    private static $ep = '/api/users';

    protected function given(string $context, ...$params)
    {
        switch ($context) {
        case 'usuario existente':
            return $this->criaUsuario();
        case 'usuarios existentes':
            return array_map(
                fn() => $this->criaUsuario(), range(1, $params[0])
            );
        }
    }

    protected function criaUsuario(array $params = [])
    {
        $s = $this->factory(
            \Core\Services\Usuario\CadastroUsuarioService::class
        );

        return $s->execute(
            nome:   $this->fakeName(),
            cpf:    $this->fakeCpf(),
            email:  $this->fakeEmail(),
            senha:  $this->fakePassword(),
            papel:  $params['papel'] ?? \Core\Models\Papel::$MEMBRO,
        );
    }

    /******** CREATE *******/

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

    /******** READ *******/

    public function testFalhaSemAutenticacaoAoListar()
    {
        $this
            ->json('GET', self::$ep)
            ->response
            ->assertUnauthorized();
    }

    public function testFalhaComoMembroAoListar()
    {
        $this
            ->jsonMembro('GET', self::$ep)
            ->response
            ->assertUnauthorized();
    }

    public function testListaComoColaboradorAdmin()
    {
        $this
            ->jsonColaborador('GET', self::$ep)
            ->response
            ->assertOk();

        $this
            ->jsonAdmin('GET', self::$ep)
            ->response
            ->assertOk();
    }

    public function testLista20Com2Paginas()
    {
        $usuarios = $this->given('usuarios existentes', 20);

        $data = array_chunk(
            array_map(fn($u) => [ 'name' => $u->nome ], $usuarios), 10,
        );

        $p1 = $data[0];
        $p2 = $data[1];

        $this
            ->jsonAdmin('GET', self::$ep)
            ->seeJsonStructure(['data'])
            ->response
            ->assertOk()
            ->assertJsonCount(10, 'data')
            ->assertJsonFragment($p1[0])
            ->assertJsonFragment($p1[1])
            ->assertJsonFragment($p1[2])
            ->assertJsonFragment($p1[3])
            ->assertJsonFragment($p1[4])
            ->assertJsonFragment($p1[5])
            ->assertJsonFragment($p1[6])
            ->assertJsonFragment($p1[7])
            ->assertJsonFragment($p1[8])
            ->assertJsonFragment($p1[9])
            ->assertJsonMissing($p2[0])
            ->assertJsonMissing($p2[1])
            ->assertJsonMissing($p2[2])
            ->assertJsonMissing($p2[3])
            ->assertJsonMissing($p2[4])
            ->assertJsonMissing($p2[5])
            ->assertJsonMissing($p2[6])
            ->assertJsonMissing($p2[7])
            ->assertJsonMissing($p2[8])
            ->assertJsonMissing($p2[9]);

        $this
            ->jsonAdmin('GET', self::$ep.'?page=2')
            ->response
            ->assertOk()
            ->assertJsonCount(10, 'data')
            ->assertJsonFragment($p2[0])
            ->assertJsonFragment($p2[1])
            ->assertJsonFragment($p2[2])
            ->assertJsonFragment($p2[3])
            ->assertJsonFragment($p2[4])
            ->assertJsonFragment($p2[5])
            ->assertJsonFragment($p2[6])
            ->assertJsonFragment($p2[7])
            ->assertJsonFragment($p2[8])
            ->assertJsonFragment($p2[9])
            ->assertJsonMissing($p1[0])
            ->assertJsonMissing($p1[1])
            ->assertJsonMissing($p1[2])
            ->assertJsonMissing($p1[3])
            ->assertJsonMissing($p1[4])
            ->assertJsonMissing($p1[5])
            ->assertJsonMissing($p1[6])
            ->assertJsonMissing($p1[7])
            ->assertJsonMissing($p1[8])
            ->assertJsonMissing($p1[9]);
    }

    /**
     * @testdox Lista por $prop parcial
     * @dataProvider propriedadesParciais
     */
    public function testListaPorNomeParcial($cbAnalise, $prop)
    {
        $usuarios = $this->given('usuarios existentes', 5);

        $data = array_map(fn($u) => $cbAnalise($u), $usuarios);

        $pesquisa = $data[3][$prop];
        $pesquisa = substr($pesquisa, 1);
        $pesquisa = substr($pesquisa, 0, -1);

        $this
            ->jsonAdmin('GET', self::$ep."?page=2&$prop=$pesquisa")
            ->seeJsonStructure(['data' => [['id']]])
            ->response
            ->assertOk()
            ->assertJsonMissing($data[0])
            ->assertJsonMissing($data[1])
            ->assertJsonMissing($data[2])
            ->assertJsonFragment($data[3])
            ->assertJsonMissing($data[4]);
    }

    public function propriedadesParciais()
    {
        yield [
            fn($u) => ['name' => $u->nome],
            'name',
        ];

        yield [
            fn($u) => ['email' => $u->email->getEmail()],
            'email',
        ];
    }

    public function testFalhaSemAutenticacaoAoListarPorId()
    {
        $this
            ->json('GET', self::$ep."/123456")
            ->response
            ->assertUnauthorized();
    }

    public function testFalhaComoMembroAoListarPorId()
    {
        $this
            ->jsonMembro('GET', self::$ep."/123456")
            ->response
            ->assertUnauthorized();
    }

    public function testListaPorId()
    {
        $u = $this->given('usuario existente');

        $this
            ->jsonAdmin('GET', self::$ep."/{$u->getId()}")
            ->seeJsonStructure(['data' => ['id',  'name']])
            ->response
            ->assertOk()
            ->assertJsonFragment(['name' => $u->nome]);
    }

    public function testFalhaSeNaoExisteAoListarPorId()
    {
        $this
            ->jsonAdmin('GET', self::$ep.'/123456')
            ->response
            ->assertNotFound();
    }

    /******** UPDATE *******/

    public function testFalhaSemAutenticacaoAoEditar()
    {
        $this
            ->json('PUT', self::$ep."/123456")
            ->response
            ->assertUnauthorized();
    }

    public function testFalhaComoMembroAoEditar()
    {
        $this
            ->jsonMembro('PUT', self::$ep."/123456")
            ->response
            ->assertUnauthorized();
    }
}
