<?php

class AuthAPITest extends E2ETestCase
{
    public function testCriaTokenJwt()
    {
        $d = $this->criaMembro();

        $email = $d['loginJWT']['email'];
        $passw = $d['loginJWT']['password'];

        $this
            ->json('POST', '/api/auth/login/jwt', [
                'email' => $email, 'password' => $passw
            ])
            ->seeJsonStructure(['token'])
            ->seeStatusCode(200);
    }

    public function testFalhaAoCriarTokenSemAutenticacaoJwt()
    {
        $d = $this->criaMembro();

        $email = $d['loginJWT']['email'];
        $passw = $d['loginJWT']['password'];

        $this
            ->json('POST', '/api/auth/login/jwt', [
                'email' => $email, 'password' => $passw.'senhaincorreta'
            ])
            ->seeJson(["Usuário ou senha inválidos"])
            ->seeStatusCode(401);
    }
}

