<?php

namespace App\DTO\Simulator;

final class CreatePaymentDTO
{
    public function __construct(
        public readonly string $gateway,
        public readonly string $apiKey,
        public readonly string $merchantId,
        public readonly string $secretKey,
        public readonly float $amount,
        public readonly string $currency,
        public readonly string $environment,
        public readonly ?string $idempotencyKey,
        public readonly ?string $internalReference,
        public readonly string $callbackUrl
    ) 
    {}

    public static function fromRequest(array $payload, string $method): self
    {
        $data = $payload;

        return new self(
            gateway: (string) $method,
            apiKey: (string) trim($data['api_key']),
            merchantId: (string) $data['merchant_id'],
            secretKey: (string) $data['secret_key'],
            amount: (float) $data['amount'],
            currency: strtoupper($data['currency']),
            environment: (string) trim($data['environment']),
            idempotencyKey: $data['idempotency_key'] ?? null,
            internalReference: $data['internal_reference'] ?? null,
            callbackUrl: (string) $data['callback_url']
        );
    }

    public function toPipeline(): array
    {
        return [
            'gateway' => $this->gateway,
            'api_key' => $this->apiKey,
            'merchant_id' => $this->merchantId,
            'secret_key_provided' => $this->secretKey,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'environment' => $this->environment,
            'idempotency_key' => $this->idempotencyKey,
            'internal_reference' => $this->internalReference,
            'callback_url' => $this->callbackUrl,
        ];
    }
}