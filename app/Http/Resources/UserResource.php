<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->getId(),
            'name' => $this->nome,
            'cpf' => $this->cpf->getNumero(),
            'email' => $this->email->getEmail(),
            'role' => $this->papel->get(),
        ];
    }
}

