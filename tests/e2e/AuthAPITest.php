<?php

use Core\Services\Usuario\CadastroUsuarioService;

use App\Repositories\Doctrine\{
    UsuariosRepository,
};
use App\Adapters\{
    LumenCryptProvider,
};

class AuthAPITest extends E2ETestCase
{
    public function testCriaTokenJwt()
    {
        $s = new CadastroUsuarioService(
            $this->factory(UsuariosRepository::class),
            $this->factory(LumenCryptProvider::class),
        );

        $email = $this->fakeEmail();
        $passw = $this->fakePassword();

        $u = $s->execute(
            nome:   $this->fakeName(),
            cpf:    $this->fakeCpf(),
            email:  $email,
            senha:  $passw,
        );

        $bodyLoginRequest = [
            'email'     => $email,
            'password'  => $passw,
        ];

        $access = $this
            ->json('GET', '/api/auth/login/jwt', $bodyLoginRequest)
            ->seeJsonStructure(['token'])
            ->seeStatusCode(200)
            ->response
            ->decodeResponseJson();
    }

    public function testFalhaAoCriarTokenSemAutenticacaoJwt()
    {
        $s = new CadastroUsuarioService(
            $this->factory(UsuariosRepository::class),
            $this->factory(LumenCryptProvider::class),
        );

        $email = $this->fakeEmail();
        $passw = $this->fakePassword();

        $u = $s->execute(
            nome:   $this->fakeName(),
            cpf:    $this->fakeCpf(),
            email:  $email,
            senha:  $passw,
        );

        $bodyLoginRequest = [
            'email'     => $email,
            'password'  => $passw.'senhaerrada',
        ];

        $access = $this
            ->json('GET', '/api/auth/login/jwt', $bodyLoginRequest)
            ->seeJson(["Usuário ou senha inválidos"])
            ->seeStatusCode(401)
            ->response
            ->getContent();
    }
}

