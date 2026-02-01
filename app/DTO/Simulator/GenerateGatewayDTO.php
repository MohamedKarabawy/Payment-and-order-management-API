<?php

namespace App\DTO\Simulator;


final class GenerateGatewayDTO
{
    public function __construct(
        public readonly string $gateway,
        public readonly string $environment
    ) 
    {}

    public static function fromRequest(array $data): self
    {
        return new self(
            gateway: trim($data['gateway']),
            environment: trim($data['environment'])
        );
    }

    public function toPipeline(): array
    {
        return [
            'gateway' => $this->gateway,
            'environment' => $this->environment
        ];
    }
}