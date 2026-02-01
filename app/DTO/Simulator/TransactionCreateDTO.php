<?php

namespace App\DTO\Simulator;

use Illuminate\Support\Str;


final class TransactionCreateDTO
{
    public function __construct(
        public readonly string $merchantId,
        public readonly string $transactionId,
        public readonly float $amount,
        public readonly string $currency,
        public readonly string $status,
        public readonly ?string $idempotencyKey,
    ) 
    {}

    public static function fromRequest(array $data): self
    {
        return new self(
            merchantId: (string) $data['merchant_id'],
            transactionId: (string) Str::uuid(),
            amount: (float) $data['amount'],
            currency: strtoupper($data['currency']),
            status: (string) "succeeded",
            idempotencyKey: $data['idempotency_key'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'merchant_id' => $this->merchantId,
            'transaction_id' => $this->transactionId,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'status' => $this->status,
            'idempotency_key' => $this->idempotencyKey,
        ];
    }
}