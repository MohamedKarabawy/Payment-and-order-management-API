<?php

namespace App\DTO\Payments;

use App\Models\Order;
use App\Models\PaymentGateway;

class PaymentDTO
{

   public function __construct(
        public readonly string $apiKey,
        public readonly string $merchantId, 
        public readonly string $secretKey,
        public readonly float $amount,
        public readonly string $currency,
        public readonly string $environment,
        public readonly string $callbackUrl
        )
   {}

   public static function fromRequest(Order $order,PaymentGateway $paymentMethod): self
    {
        return new self(
            apiKey: trim($paymentMethod->api_key),
            merchantId: trim($paymentMethod->merchant_id),
            secretKey: trim($paymentMethod->secret_key),
            environment: trim($paymentMethod->environment),
            amount: (float) $order->total_price,
            currency: trim($order->currency),
            callbackUrl: url('/')
        );
    }

    public function toArray(): array
    {
        return [
            'api_key' => $this->apiKey,
            'merchant_id' => $this->merchantId,
            'secret_key' => $this->secretKey,
            'environment' => $this->environment,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'callback_url' => $this->callbackUrl
        ];
    }
}