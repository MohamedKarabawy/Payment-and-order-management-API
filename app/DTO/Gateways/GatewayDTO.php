<?php

namespace App\DTO\Gateways;

class GatewayDTO
{

   public function __construct(
        public readonly string $merchantId, 
        public readonly string $apiKey,
        public readonly string $secretKey,
        public readonly string $environment,
        public readonly string $status
        )
   {}

   public static function fromRequest(array $data): self
    {
        return new self(
            merchantId: trim($data['merchantId']),
            apiKey: trim($data['apiKey']) ?? '',
            secretKey: trim($data['secretKey']),
            environment: trim($data['environment']),
            status: trim($data['status'])
        );
    }

    public function toArray(): array
    {
        return [
            'merchant_id' => $this->merchantId,
            'api_key' => $this->apiKey,
            'secret_key' => $this->secretKey,
            'environment' => $this->environment,
            'status' => $this->status
        ];
    }
}