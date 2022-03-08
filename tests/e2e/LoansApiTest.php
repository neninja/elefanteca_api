<?php

use Core\Models\{
    Papel,
};

class LoansAPITest extends E2ETestCase
{
    private static $ep = '/api/loans';

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
        case 'emprestimos existentes':
            return array_map(
                fn() => $this->criaEmprestimo(), range(1, $params[0])
            );
        }
    }

    /******** CREATE *******/

    public function testFalhaSemAutenticacaoAoCriar()
    {
        $this
            ->json('POST', self::$ep)
            ->response
            ->assertUnauthorized();
    }

    public function testFalhaComoMembroAoCriar()
    {
        $this
            ->jsonMembro('POST', self::$ep)
            ->response
            ->assertUnauthorized();
    }

    public function testCriaComoColaborador()
    {
        $l = $this->given('livro existente');
        $m = $this->given('membro existente');
        $c = $this->given('colaborador existente');

        $req = [
            'bookId'       => $l->getId(),
            'memberId'      => $m->getId(),
            'collaboratorId' => $c->getId(),
        ];

        $this
            ->jsonColaborador('POST', self::$ep, $req)
            ->seeJsonStructure(
                ['data' => ['id', 'book', 'collaboratorUser', 'memberUser', 'loanDate']
            ])
            ->response
            ->assertOk();

        $this->seeInDatabase('emprestimos', [
            'id_livro'               => $req['bookId'],
            'id_usuario_membro'      => $req['memberId'],
            'id_usuario_colaborador' => $req['collaboratorId'],
        ]);
    }

    public function testCriaComoAdmin()
    {
        $l = $this->given('livro existente');
        $m = $this->given('membro existente');
        $c = $this->given('colaborador existente');

        $req = [
            'bookId'       => $l->getId(),
            'memberId'      => $m->getId(),
            'collaboratorId' => $c->getId(),
        ];

        $this
            ->jsonAdmin('POST', self::$ep, $req)
            ->seeJsonStructure(
                ['data' => ['id', 'book', 'collaboratorUser', 'memberUser', 'loanDate']
            ])
            ->response
            ->assertOk();

        $this->seeInDatabase('emprestimos', [
            'id_livro'               => $req['bookId'],
            'id_usuario_membro'      => $req['memberId'],
            'id_usuario_colaborador' => $req['collaboratorId'],
        ]);
    }

    /******** READ *******/

    public function testFalhaSemAutenticacaoAoListar()
    {
        $this
            ->json('GET', self::$ep)
            ->response
            ->assertUnauthorized();
    }

    public function testFalhaComoMembroAoListar()
    {
        $this
            ->jsonMembro('GET', self::$ep)
            ->response
            ->assertUnauthorized();
    }

    public function testLista20Com2Paginas()
    {
        $this->given('emprestimos existentes', 20);

        $this
            ->jsonColaborador('GET', self::$ep)
            ->seeJsonStructure(['data'])
            ->response
            ->assertOk()
            ->assertJsonCount(10, 'data');

        $this
            ->jsonColaborador('GET', self::$ep.'?page=2')
            ->seeJsonStructure(['data'])
            ->response
            ->assertOk()
            ->assertJsonCount(10, 'data');
    }

    public function testListaPorLivro()
    {
        $emprestimos = $this->given('emprestimos existentes', 5);

        $bookId = $emprestimos[3]->livro->getId();

        $this
            ->jsonColaborador('GET', self::$ep.'?bookId='.$bookId)
            ->response
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function testListaPorMembro()
    {
        $emprestimos = $this->given('emprestimos existentes', 5);

        $memberId = $emprestimos[3]->usuarioMembro->getId();

        $this
            ->jsonColaborador('GET', self::$ep.'?memberId='.$memberId)
            ->response
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function testFalhaSemAutenticacaoAoListarPorId()
    {
        $this
            ->json('GET', self::$ep."/123456")
            ->response
            ->assertUnauthorized();
    }

    public function testFalhaComoMembroAoListarPorId()
    {
        $this
            ->jsonMembro('GET', self::$ep."/123456")
            ->response
            ->assertUnauthorized();
    }

    public function testListaPorId()
    {
        $e = $this->given('emprestimo existente');

        $this
            ->jsonColaborador('GET', self::$ep."/{$e->getId()}")
            ->seeJsonStructure(['data'])
            ->response
            ->assertOk();
    }

    public function testFalhaSeNaoExisteAoListarPorId()
    {
        $this
            ->jsonColaborador('GET', self::$ep.'/123456')
            ->response
            ->assertNotFound();
    }
}

