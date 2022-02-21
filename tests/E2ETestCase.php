<?php

use Core\Models\Papel;

abstract class E2ETestCase extends LumenTestCase
{
    private function jsonComo(
        \App\Models\User $user,
        string $method,
        string $ep,
        array $body = [],
        $headers = []
    ) {
        return $this
            ->actingAs($user)
            ->json($method, $ep, $body, $headers);
    }

    protected function jsonComoAdmin(
        string $method, string $ep, array $body = [], $headers = []
    ) {
        return $this->jsonComo(
            $this->criaAdmin(),
            $method,
            $ep,
            $body,
            $headers,
        );
    }

    protected function jsonComoMembro(
        string $method, string $ep, array $body = [], $headers = []
    ) {
        return $this->jsonComo(
            $this->criaMembro(),
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
            $this->criaColaborador(),
            $method,
            $ep,
            $body,
            $headers,
        );
    }

    protected function criaUsuario($papel)
    {
        $faker = Faker\Factory::create('pt_BR');

        $u = new \App\Models\User();
        $u->name = $this->fakeName();
        $u->email = $this->fakeEmail();
        $u->role = $papel;

        return $u;
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
