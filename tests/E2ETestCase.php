<?php

use Core\Services\Usuario\CadastroUsuarioService;

use Core\Models\Papel;

abstract class E2ETestCase extends LumenTestCase
{
    protected function jsonComoColaborador(
        string $method, string $ep, array $body = [], $headers = []
    ) {
        $bodyLoginRequest = $this->criaColaborador()['loginJWT'];

        $access = $this
            ->json('GET', '/api/auth/login/jwt', $bodyLoginRequest)
            ->response
            ->decodeResponseJson();

        $headers['Authorization'] = "Bearer {$access['token']}";

        return $this->json($method, $ep, $body, $headers);
    }

    protected function criaUsuario($papel)
    {
        $faker = Faker\Factory::create('pt_BR');

        $s = $this->factory(CadastroUsuarioService::class);

        $email = $faker->email();
        $passw = $faker->password();

        $u = $s->execute(
            nome:   "Colaborador {$faker->name()}",
            cpf:    $faker->cpf(false),
            email:  $email,
            senha:  $passw,
            papel:  $papel
        );

        return [
            'loginJWT' => [
                'email'     => $email,
                'password'  => $passw,
            ],
            'usuario' => $u,
        ];
    }

    protected function criaColaborador()
    {
        return $this->criaUsuario(Papel::$COLABORADOR);
    }
}
