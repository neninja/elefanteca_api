<?php

use Core\Models\{
    CPF,
    Email,
    Papel,
    Autor,
    Livro,
    Usuario,
    Emprestimo,
};

use Core\Exceptions\CoreException;

class EmprestimoTest extends \PHPUnit\Framework\TestCase
{
    use Fake;

    public function testReservaParaDuasSemanas()
    {
        $autor = new Autor(
            nome: $this->fakeName(),
        );

        $livro = new Livro(
            titulo: $this->fakeWords(),
            autor: $autor,
            quantidade: 1,
        );

        $membro = new Usuario(
            nome:   $this->fakeName(),
            cpf:    new CPF($this->fakeCpf()),
            senha:  $this->fakePassword(),
            email:  new Email($this->fakeEmail()),
            papel:  new Papel(Papel::$MEMBRO),
        );

        $colaborador = new Usuario(
            nome:   $this->fakeName(),
            cpf:    new CPF($this->fakeCpf()),
            senha:  $this->fakePassword(),
            email:  new Email($this->fakeEmail()),
            papel:  new Papel(Papel::$COLABORADOR),
        );

        $emprestimo = new Emprestimo(
            livro:              $livro,
            usuarioMembro:      $membro,
            usuarioColaborador: $colaborador,
            dataEmprestimo:     new \datetimeimmutable(),
        );

        $this->assertEquals(
            (new \DateTime())->modify('2 weeks')->format('Y-m-d'),
            $emprestimo->dataEntregaPrevista->format('Y-m-d')
        );
    }
}
