<?php

use Core\Services\Emprestimo\DevolucaoService;
use Core\Models\{
    Emprestimo,
    Usuario,
    Papel,
};

use App\Repositories\Doctrine\{
    EmprestimosRepository,
    UsuariosRepository,
    LivrosRepository,
};

use Core\Exceptions\CoreException;

class DevolucaoServiceTest extends IntegrationTestCase
{
    private function sut()
    {
        return new DevolucaoService(
            $this->factory(EmprestimosRepository::class),
            $this->factory(LivrosRepository::class),
            $this->factory(UsuariosRepository::class),
        );
    }

    private function emprestimoPersistido(Emprestimo $e): Emprestimo
    {
        return $this->persistidoById(Emprestimo::class, $e->getId());
    }

    protected function criaUsuario(?array $params = null)
    {
        $s = $this->factory(
            \Core\Services\Usuario\CadastroUsuarioService::class
        );

        return $s->execute(
            nome:   $params['nome'] ?? $this->fakeName(),
            cpf:    $params['cpf'] ?? $this->fakeCpf(),
            email:  $params['email'] ?? $this->fakeEmail(),
            senha:  $this->fakePassword(),
            papel:  $params['papel'] ?? Papel::$MEMBRO,
        );
    }

    protected function criaLivro(?array $params = null)
    {
        $a = $this
            ->factory(\Core\Services\Emprestimo\CadastroAutorService::class)
            ->execute($this->fakeName());

        return $this
            ->factory(\Core\Services\Emprestimo\CadastroLivroService::class)
            ->execute(
                titulo:     $this->fakeWords(),
                idAutor:    $a->getId(),
                quantidade: $params['quantidade'] ?? 1,
            );
    }

    protected function criaEmprestimo(?array $params = null)
    {
        $l = $this->given('livro existente');
        $m = $this->given('membro existente');
        $c = $this->given('colaborador existente');

        return $this
            ->factory(\Core\Services\Emprestimo\EmprestimoService::class)
            ->execute(
                idLivro:        $params['idLivro'] ?? $l->getId(),
                idMembro:       $params['idMembro'] ?? $m->getId(),
                idColaborador:  $params['idColanorador'] ?? $c->getId(),
            );
    }

    protected function given(string $context, ...$params)
    {
        switch ($context) {
        case 'membro existente':
            return $this->criaUsuario(['papel' => Papel::$MEMBRO]);
        case 'colaborador existente':
            return $this->criaUsuario(['papel' => Papel::$COLABORADOR]);
        case 'livro existente':
            return $this->criaLivro($params[0] ?? null);
        case 'emprestimo existente':
            return $this->criaEmprestimo($params[0] ?? null);
        }
    }

    public function testCriaComValoresObrigatorios()
    {
        $e = $this->given('emprestimo existente');

        $emprestimo = $this->sut()->execute(
            idEmprestimo: $e->getId(),
        );

        $this->assertNotNull($emprestimo->dataEntregaRealizada);

        $persistido = $this->emprestimoPersistido($emprestimo);

        $this->assertEquals(
            $emprestimo->dataEntregaRealizada->format('Y-m-d'),
            $persistido->dataEntregaRealizada->format('Y-m-d')
        );
    }

    public function testFalhaAoDevolverEmDuplicidade()
    {
        $e = $this->given('emprestimo existente');

        $emprestimo = $this->sut()->execute(
            idEmprestimo: $e->getId(),
        );

        $this->expectException(CoreException::class);
        $this->expectExceptionMessage('Empréstimo indisponível');

        $this->sut()->execute(
            idEmprestimo: $e->getId(),
        );
    }

    public function testFalhaAoDevolverEmprestimoInexistente()
    {
        $this->expectException(CoreException::class);
        $this->expectExceptionMessage('Empréstimo indisponível');

        $emprestimo = $this->sut()->execute(
            idEmprestimo: 123456,
        );
    }
}

