<?php

namespace Core\Models;

class Papel
{
    public static string $ADMIN = 'admin';
    public static string $COLABORADOR = 'colaborador';
    public static string $MEMBRO = 'membro';

    public function __construct(
        public string $papel,
    ) {
        if(!in_array(
            $papel, [self::$ADMIN, self::$COLABORADOR, self::$MEMBRO]
        )) {
            throw new ValidationException("Papel inválido", $papel);
        }
    }

    public function get()
    {
        return $this->papel;
    }
}
