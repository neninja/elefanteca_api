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

    protected function jsonAdmin(
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

    protected function jsonMembro(
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

    protected function jsonColaborador(
        string $method, string $ep, array $body = [], $headers = []
    ) {
        return $this->jsonComo(
            $this->criaColaborador(),
            $method,
            $ep,
            $body,
            $headers,
        );
    }

    private function criaUser($papel)
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
        return $this->criaUser(Papel::$MEMBRO);
    }

    protected function criaColaborador()
    {
        return $this->criaUser(Papel::$COLABORADOR);
    }

    protected function criaAdmin()
    {
        return $this->criaUser(Papel::$ADMIN);
    }
}
