<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'     => $this->getId(),
            'title'  => $this->titulo,
            'author' => new AuthorResource($this->autor),
            'amount' => $this->quantidade,
            'active' => $this->getAtivo(),
        ];
    }
}

