<?php

namespace App\Payments;

use App\Contracts\Payments\PaymentMethodInterface;
use App\Simulator\Payment;

class PaypalPaymentMethod implements PaymentMethodInterface
{
    private string $paymentMethod;

    public function __construct(protected Payment $payment)
    {
        $this->paymentMethod = 'paypal';
    }

    public function pay(array $payload): array
    {
        return $this->payment->simulatePayment($payload, $this->paymentMethod);
    }

}