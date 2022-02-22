<?php

namespace Core\Models;

use Core\Exceptions\ValidationException;

class CPF
{
    private string $numero;

    public function __construct($numero)
    {
        if(!$this->cpfValido($numero))
            throw new ValidationException("CPF inválido", $numero);

        $this->numero = $numero;
    }

    private function cpfValido($cpf)
    {
        if(strlen($cpf) !== 11){
            return false;
        }

        $ehUmDosCpfInvalidos = in_array(
            $cpf,
            [
                "00000000000",
                "11111111111",
                "22222222222",
                "33333333333",
                "44444444444",
                "55555555555",
                "66666666666",
                "77777777777",
                "88888888888",
                "99999999999"
            ]
        );
        if($ehUmDosCpfInvalidos){
            return false;
        }

        $c = str_split($cpf);
        $c = array_map(fn($c) => intval($c), $c);

		$somaPrimeiroDigito =
				$c[0] * 10 +
				$c[1] * 9 +
				$c[2] * 8 +
				$c[3] * 7 +
				$c[4] * 6 +
				$c[5] * 5 +
				$c[6] * 4 +
				$c[7] * 3 +
				$c[8] * 2;
		$restoSomaPrimeiroDigito = $somaPrimeiroDigito % 11;

        $primeiroDigito = $restoSomaPrimeiroDigito < 2
            ? 0 : 11 - $restoSomaPrimeiroDigito;

		$somaSegundoDigito =
				$c[0] * 11 +
				$c[1] * 10 +
				$c[2] * 9 +
				$c[3] * 8 +
				$c[4] * 7 +
				$c[5] * 6 +
				$c[6] * 5 +
				$c[7] * 4 +
				$c[8] * 3 +
				$c[9] * 2;

		$restoSomaSegundoDigito = $somaSegundoDigito % 11;
        $segundoDigito = $restoSomaSegundoDigito < 2
            ? 0 : 11 - $restoSomaSegundoDigito;

        if(
            $c[9] === $primeiroDigito
            && $c[10] === $segundoDigito
        ){
			return true;
		}

        return false;
    }

    public function formatado()
    {
        $cpf =
            substr($this->numero, 0, 3).".".
            substr($this->numero, 3, 3).".".
            substr($this->numero, 6, 3)."-".
            substr($this->numero, 9, 2);

        return $cpf;
    }

    public function getNumero()
    {
        return $this->numero;
    }
}
