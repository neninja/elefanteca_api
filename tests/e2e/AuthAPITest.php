<?php

class AuthAPITest extends E2ETestCase
{
    public function testCriaTokenJwt()
    {
        $d = $this->criaMembro();

        $email = $d['loginJWT']['email'];
        $passw = $d['loginJWT']['password'];

        $token = $this
            ->json('POST', '/api/auth/login/jwt', [
                'email' => $email, 'password' => $passw
            ])
            ->seeStatusCode(200)
            ->response
            ->getContent();

        $this->assertNotEmpty($token);
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

