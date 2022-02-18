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
    public function testCriaTokenJWT()
    {
        $faker = Faker\Factory::create('pt_BR');

        $s = new CadastroUsuarioService(
            $this->factory(UsuariosRepository::class),
            $this->factory(LumenCryptProvider::class),
        );

        $email = $faker->email();
        $passw = $faker->password();

        $u = $s->execute(
            nome:   $faker->name(),
            cpf:    $faker->cpf(false),
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

    public function testFalhaAoCriarTokenSemAutenticacaoJWT()
    {
        $faker = Faker\Factory::create('pt_BR');

        $s = new CadastroUsuarioService(
            $this->factory(UsuariosRepository::class),
            $this->factory(LumenCryptProvider::class),
        );

        $email = $faker->email();
        $passw = $faker->password();

        $u = $s->execute(
            nome:   $faker->name(),
            cpf:    $faker->cpf(false),
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

