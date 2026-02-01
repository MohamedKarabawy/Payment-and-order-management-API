<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->product_name,
            'description' => $this->resource->product_description,
            'price' => $this->resource->product_price,
            'stock_quantity' => $this->resource->stock_no,
        ];
    }
}
