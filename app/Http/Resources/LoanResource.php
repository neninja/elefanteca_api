<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                    => $this->getId(),
            'book'                  => new BookResource($this->livro),
            'memberUser'            => new UserResource($this->usuarioMembro),
            'collaboratorUser'      => new UserResource($this->usuarioColaborador),
            'loanDate'              => $this->dataEmprestimo,
            'expectedDeliveryDate'  => $this->dataEntregaPrevista,
            'realizedDeliveryDate'  => $this->dataEntregaRealizada,
        ];
    }
}
