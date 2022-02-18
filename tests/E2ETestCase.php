<?php

use Core\Services\Usuario\CadastroUsuarioService;

use App\Repositories\Doctrine\{
    UsuariosRepository,
};
use App\Adapters\{
    LumenCryptProvider,
};

abstract class E2ETestCase extends LumenTestCase
{
    protected function jsonComoColaborador(
        string $method, string $ep, array $body = [], $headers = []
    ) {
        $faker = Faker\Factory::create('pt_BR');

        $s = new CadastroUsuarioService(
            $this->factory(UsuariosRepository::class),
            $this->factory(LumenCryptProvider::class),
        );

        $email = $faker->email();
        $passw = $faker->password();

        $u = $s->execute(
            nome:   "Colaborador {$faker->name()}",
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
            ->response
            ->decodeResponseJson();

        $headers['Authorization'] = "Bearer {$access['token']}";

        return $this->json($method, $ep, $body, $headers);
    }
}
