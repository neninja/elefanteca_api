<?php

use Core\Services\Usuario\{
    CadastroUsuarioService,
    EdicaoUsuarioService,
};
use Core\Models\Usuario;

use App\Repositories\Doctrine\{
    UsuariosRepository,
};

use Core\Exceptions\CoreException;

class EdicaoUsuarioServiceTest extends IntegrationTestCase
{
    private function sut()
    {
        return new EdicaoUsuarioService(
            $this->factory(UsuariosRepository::class),
        );
    }

    private function usuarioPersistido(Usuario $u): Usuario
    {
        return $this->persistidoById(Usuario::class, $u->getId());
    }

    protected function given(string $context, ...$params)
    {
        switch ($context) {
        case 'usuario existente':
            return $this->criaUsuario($params[0] ?? null);
        case 'usuarios existentes':
            return array_map(
                fn() => $this->criaUsuario(), range(1, $params[0])
            );
        }
    }

    protected function criaUsuario(?array $params = null)
    {
        $s = $this->factory(
            \Core\Services\Usuario\CadastroUsuarioService::class
        );

        return $s->execute(
            nome:   $params['papel'] ?? $this->fakeName(),
            cpf:    $params['papel'] ?? $this->fakeCpf(),
            email:  $params['papel'] ?? $this->fakeEmail(),
            senha:  $this->fakePassword(),
            papel:  $params['papel'] ?? \Core\Models\Papel::$MEMBRO,
        );
    }

    private function fixture($contexto)
    {
        switch($contexto){
        case 'ok':
            return [
                'nome'  => $this->fakeName(),
                'cpf'   => $this->fakeCpf(),
                'email' => $this->fakeEmail(),
                'senha' => $this->fakePassword(),
            ];
        default:
            throw new \InvalidArgumentException();
        }
    }

    public function testPersisteComNomeCpfEmailCorretos()
    {
        $u = $this->given('usuario existente');
        $fixture = $this->fixture('ok');

        $usuario = $this->sut()->execute(
            id:     $u->getId(),
            nome:   $fixture['nome'],
            cpf:    $fixture['cpf'],
            email:  $fixture['email'],
        );

        $persistido = $this->usuarioPersistido($usuario);
        $this->assertEquals(
            $usuario->getId(),
            $persistido->getId()
        );
        $this->assertEquals(
            $usuario->cpf,
            $persistido->cpf
        );
        $this->assertEquals(
            $usuario->email,
            $persistido->email
        );
    }

    public function testFalhaAoEditarComEmailNovoJaUtilizado()
    {
        $email = 'emailquenaodeveserduplicado@email.com';
        $usuarioExistenteComEmailFinal = $this->given('usuario existente', [
            'email' => $email
        ]);

        $usuarioQueSeraEditado = $this->given('usuario existente');

        $fixture = $this->fixture('ok');

        $this->expectException(CoreException::class);
        $this->expectExceptionMessage('Email já cadastrado');

        $usuario = $this->sut()->execute(
            id:     $usuarioQueSeraEditado->getId(),
            nome:   $fixture['nome'],
            cpf:    $fixture['cpf'],
            email:  $usuarioExistenteComEmailFinal->email->getEmail(),
        );

        $persistido = $this->usuarioPersistido($usuario);
    }

    public function testFalhaAoEditarComCpfNovoJaUtilizado()
    {
        $cpf = $this->fakeCpf();
        $usuarioExistenteComCpfFinal = $this->given('usuario existente', [
            'cpf' => $cpf
        ]);

        $usuarioQueSeraEditado = $this->given('usuario existente');

        $fixture = $this->fixture('ok');

        $this->expectException(CoreException::class);
        $this->expectExceptionMessage('Cpf já cadastrado');

        $usuario = $this->sut()->execute(
            id:     $usuarioQueSeraEditado->getId(),
            nome:   $fixture['nome'],
            cpf:    $usuarioExistenteComCpfFinal->cpf->getNumero(),
            email:  $fixture['email'],
        );

        $persistido = $this->usuarioPersistido($usuario);
    }
}
