<?php

namespace App\Http\Resources\Simulator;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'gateway_transaction_id' => $this->transaction_id,
            'merchant_id' => $this->merchant_id,
            'amount' => (float) $this->amount,
            'currency' => $this->currency,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
