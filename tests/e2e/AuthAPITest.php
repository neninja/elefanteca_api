<?php

use Core\Services\Usuario\CadastroUsuarioService;

use Core\Models\Papel;

class AuthAPITest extends E2ETestCase
{
    protected function criaUsuario($papel)
    {
        $s = $this->factory(CadastroUsuarioService::class);

        $email = $this->fakeEmail();
        $passw = $this->fakePassword();

        $u = $s->execute(
            nome:   "Colaborador {$this->fakeName()}",
            cpf:    $this->fakeCpf(),
            email:  $email,
            senha:  $passw,
            papel:  $papel,
        );

        return [
            'loginJWT' => [
                'email'     => $email,
                'password'  => $passw,
            ],
            'usuario' => $u,
        ];
    }

    public function testCriaTokenJwt()
    {
        $loginJWT = $this->criaUsuario(Papel::$MEMBRO)['loginJWT'];
        $token = $this
            ->json('POST', '/api/auth/login/jwt', $loginJWT)
            ->response
            ->assertOk()
            ->getContent();

        $this->assertNotEmpty($token);
    }

    public function testFalhaAoCriarTokenSemAutenticacaoJwt()
    {
        $login = $this->criaUsuario(Papel::$MEMBRO)['loginJWT'];

        $email = $login['email'];
        $passw = $login['password'];

        $this
            ->json('POST', '/api/auth/login/jwt', [
                'email' => $email, 'password' => $passw.'senhaincorreta'
            ])
            ->seeJson(["Usuário ou senha inválidos"])
            ->response
            ->assertUnauthorized();
    }
}

