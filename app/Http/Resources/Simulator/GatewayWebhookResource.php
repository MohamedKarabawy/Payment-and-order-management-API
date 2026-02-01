<?php

namespace App\Http\Resources\Simulator;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GatewayWebhookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'gateway' => $this->gateway_name,
            'merchant_id' => $this->merchant_id,
            'transaction_id' => $this->transaction_id,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'status' => $this->status,
            'event' => $this->event_type,
            'timestamp' => now()->toIso8601String()
        ];
    }
}
