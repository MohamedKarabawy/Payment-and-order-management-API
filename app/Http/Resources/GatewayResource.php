<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GatewayResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
          return [
            'gatewayName' => $this->resource->gateway_name,
            'merchantId' => $this->resource->merchant_id,
            'apiKey' => $this->resource->api_key,
            'secretKey' => $this->resource->secret_key,
            'environment' => $this->resource->environment,
            'status' => $this->resource->status
        ];
    }
}
