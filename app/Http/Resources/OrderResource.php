<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'order_no' => $this->resource->order_no,
            'status' => $this->resource->status,
            'currency' => $this->resource->currency,
            'customer' => [
                'name' => $this->resource->customer_name,
                'email' => $this->resource->billing_email,
                'address' => $this->resource->billing_address,
                'country' => $this->resource->billing_country,
                'phone' => $this->resource->billing_phone,
                'zip_code' => $this->resource->billing_zip_code,
            ],
            'total_price' => $this->resource->total_price,
            'payment_method' => $this->resource->payment_method,
            'transaction_id' => $this->resource->transaction_id,
            'payment_status' => $this->resource->payment_status,
            // 'items' => $this->whenLoaded('items', function () {
            //     return $this->items->map(fn($item) => [
            //         'name' => $item->pivot->product_name,
            //         'price' => $item->pivot->product_price,
            //     ]);
            // })

            'items' => $this->whenLoaded('orderItems', function () {
                return $this->orderItems->map(fn($orderItem) => [
                    'name' => $orderItem->product_name,
                    'price' => $orderItem->product_price,
                    'quantity' => $orderItem->product_quantity
                ]);
            }),
        ];
    }
}
