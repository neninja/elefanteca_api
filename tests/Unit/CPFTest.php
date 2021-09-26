<?php

use Core\Models\CPF;

use Core\Exceptions\CoreException;

/**
 * @covers \Core\Models\CPF::<!private>
 */
class CPFTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox Deve formatar $cru para $formatado
     * @dataProvider cpfsFormatados
     */
    public function testDeveFormatar($cru, $formatado)
    {
        $cpf = new CPF($cru);
        $this->assertEquals(
            $cpf->formatado(), $formatado
        );
    }

    public function cpfsFormatados():array
    {
        // https://tiagoporto.github.io/gerador-validador-cpf/
        return [
            ['81026092140', '810.260.921-40'],
            ['84820226959', '848.202.269-59'],
            ['74442704309', '744.427.043-09']
        ];
    }

    /**
     * @testdox Deve falhar como "CPF inválido" $cpf
     * @dataProvider cpfsInvalidos
     */
    public function testDeveFalharComErroAmigavel($cpf)
    {
        try {
            $cpf = new CPF($cpf);
            self::fail('Deve cair no catch');
        } catch (CoreException $e) {
            self::assertEquals(
                $e->mensagemAmigavel(), "CPF inválido"
            );
        }
    }

    public function cpfsInvalidos()
    {
        return [
            ['00000000000'],
            ['11111111111'],
            ['22222222222'],
            ['33333333333'],
            ['44444444444'],
            ['55555555555'],
            ['66666666666'],
            ['77777777777'],
            ['88888888888'],
            ['99999999999'],
            ['81026092141']
        ];
    }
}
