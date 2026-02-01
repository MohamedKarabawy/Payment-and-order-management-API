<?php

namespace App\Payments;

use App\Contracts\Payments\PaymentMethodInterface;
use App\Simulator\Payment;

class StripePaymentMethod implements PaymentMethodInterface
{
    private string $paymentMethod;

    public function __construct(protected Payment $payment)
    {
        $this->paymentMethod = 'stripe';
    }

    public function pay(array $payload): array
    {
        return $this->payment->simulatePayment($payload, $this->paymentMethod);
    }

}