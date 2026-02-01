<?php

namespace App\DTO\Payments; 

use App\Models\Order;
use App\Models\PaymentGateway;

class StorePaymentDTO
{
    public function __construct(
        public readonly int $orderId,
        public readonly string $gatewayName,
        public readonly string $transactionId,
        public readonly float $amount,
        public readonly string $currency,
        public readonly string $status
    ) {
    }

    public static function fromRequest(Order $order, array $payment, PaymentGateway $paymentMethod): self
    {
        return new self(
            orderId: $order->id,
            gatewayName: $paymentMethod->gateway_name,
            transactionId: $payment['gateway_transaction_id'],
            amount: $payment['amount'],
            currency: $payment['currency'],
            status: "paid"
        );
    }

    public function toArray(): array
    {
        return [
            'order_id' => $this->orderId,
            'gateway_name' => $this->gatewayName,
            'transaction_id' => $this->transactionId,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'status' => $this->status
        ];
    }
}