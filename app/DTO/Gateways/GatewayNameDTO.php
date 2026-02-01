<?php

namespace App\DTO\Gateways;

class GatewayNameDTO
{

   public function __construct(
        public readonly string $gatewayName, 
        )
   {}

   public static function fromRequest(array $data): self
    {
        return new self(
            gatewayName: trim($data['gateway']),
        );
    }

    public function toPipeline(): array
    {
        return [
            'gateway' => $this->gatewayName
        ];
    }
}