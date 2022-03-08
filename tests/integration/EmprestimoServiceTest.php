<?php

use Core\Services\Emprestimo\EmprestimoService;
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

class EmprestimoServiceTest extends IntegrationTestCase
{
    private function sut()
    {
        return new EmprestimoService(
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

    private function fixture($contexto)
    {
        switch($contexto){
        case 'ok':
            $colaborador = $this->given('colaborador existente');
            $membro      = $this->given('membro existente');
            $livro       = $this->given('livro existente');

            return [
                'idColaborador' => $colaborador->getId(),
                'idMembro'      => $membro->getId(),
                'idLivro'       => $livro->getId(),
            ];
        }
    }

    public function testCriaComValoresObrigatorios()
    {
        $fixture = $this->fixture('ok');

        $emprestimo = $this->sut()->execute(
            idColaborador:  $fixture['idColaborador'],
            idMembro:       $fixture['idMembro'],
            idLivro:        $fixture['idLivro'],
        );

        $this->assertNotNull($emprestimo->getId());

        $persistido = $this->emprestimoPersistido($emprestimo);
        $this->assertEquals(
            $emprestimo->getId(),
            $persistido->getId()
        );
    }

    public function testFalhaAoCriarSemLivroNoEstoque()
    {
        $l = $this->given('livro existente', ['quantidade' => 1]);
        $m = $this->given('membro existente');
        $c = $this->given('colaborador existente');

        $this->given('emprestimo existente', ['idLivro' => $l->getId()]);

        $this->expectException(CoreException::class);
        $this->expectExceptionMessage('Livro indisponível');

        $emprestimo = $this->sut()->execute(
            idLivro:        $l->getId(),
            idMembro:       $m->getId(),
            idColaborador:  $c->getId(),
        );
    }
}
