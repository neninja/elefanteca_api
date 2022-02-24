<?php

namespace App\Http\Controllers;

use Firebase\JWT\JWT;
use Illuminate\Http\Request;

use Core\Services\{
    Usuario\LoginEmailSenhaService,
};

class AuthController extends Controller
{
    public function __construct(
        private LoginEmailSenhaService $loginEmailSenhaService
    ) {}

    /**
     * @OA\Post(
     *     tags={"auth"},
     *     path="/api/auth/login/jwt",
     *     description="Login com JWT",
     *     @OA\RequestBody(
     *         @OA\MediaType(mediaType="application/json;charset=UTF-8",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      example="admin@desativemeemprod.com",
     *                 ),
     *                 @OA\Property(
     *                      property="password",
     *                      type="string",
     *                      example="asdf",
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(response="2XX", description="OK"),
     * )
     */
    public function loginJWT(Request $r)
    {
        $this->validate($r, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $ok = $this->loginEmailSenhaService->execute($r->email, $r->password);

        if (!$ok) {
            return response()->json('Usuário ou senha inválidos', 401);
        }

        $token = JWT::encode(
            ['email' => $r->email],
            env('JWT_KEY'),
            'HS256'
        );

        return $token;
    }
}
