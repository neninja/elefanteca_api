<?php

use Core\Models\Email;

use Core\Exceptions\CoreException;

class EmailTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox Falha como "Email inválido" $email
     * @dataProvider emailsInvalidos
     */
    public function testFalhaComErroAmigavel($email)
    {
        $this->expectException(CoreException::class);
        $this->expectExceptionMessage('Email inválido');

        $cpf = new Email($email);
    }

    public function emailsInvalidos()
    {
        return [
            ['exemplo'],
            ['exemplo@'],
            ['exemplo.com'],
            ['exemplo@.com'],
            ['@com'],
            ['@.com'],
            ['exemplo@com'],
        ];
    }
}
