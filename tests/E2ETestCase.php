<?php

use Core\Services\Usuario\CadastroUsuarioService;

use Core\Models\Papel;

abstract class E2ETestCase extends LumenTestCase
{
    private function jsonComo(
        string $email,
        string $password,
        string $method,
        string $ep,
        array $body = [],
        $headers = []
    ) {
        $token = $this
            ->json('POST', '/api/auth/login/jwt', [
                'email' => $email, 'password' => $password
            ])
            ->response
            ->getContent();

        $headers['Authorization'] = "Bearer {$token}";

        return $this->json($method, $ep, $body, $headers);
    }

    protected function jsonComoAdmin(
        string $method, string $ep, array $body = [], $headers = []
    ) {
        $login = $this->criaAdmin()['loginJWT'];

        return $this->jsonComo(
            $login['email'],
            $login['password'],
            $method,
            $ep,
            $body,
            $headers,
        );
    }

    protected function jsonComoMembro(
        string $method, string $ep, array $body = [], $headers = []
    ) {
        $login = $this->criaMembro()['loginJWT'];

        return $this->jsonComo(
            $login['email'],
            $login['password'],
            $method,
            $ep,
            $body,
            $headers,
        );
    }

    protected function jsonComoColaborador(
        string $method, string $ep, array $body = [], $headers = []
    ) {
        $login = $this->criaColaborador()['loginJWT'];

        return $this->jsonComo(
            $login['email'],
            $login['password'],
            $method,
            $ep,
            $body,
            $headers,
        );
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

    protected function criaMembro()
    {
        return $this->criaUsuario(Papel::$MEMBRO);
    }

    protected function criaColaborador()
    {
        return $this->criaUsuario(Papel::$COLABORADOR);
    }

    protected function criaAdmin()
    {
        return $this->criaUsuario(Papel::$ADMIN);
    }
}
