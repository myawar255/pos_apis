<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'product_id' => $this->id,
            'name' => $this->name,
            'barcode' => $this->barcode,
            'price' => $this->price,
            'quantity' => $this->pivot->quantity,
            'stock' => $this->quantity,
            'subtotal' => $this->price * $this->pivot->quantity,
        ];
    }
}
