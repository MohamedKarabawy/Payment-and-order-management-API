<?php

namespace App\Gateways;

use App\Contracts\Gateways\PaymentGatewayInterface;
use InvalidArgumentException;

class PaymentGatewayResolver
{
    public function resolve(string $gateway): PaymentGatewayInterface
    {
        return match($gateway)
        {
            'paypal' => app(PaypalPaymentGateway::class),

            'stripe' => app(StripePaymentGateway::class),

            'visa' => app(VisaPaymentGateway::class),

            default => throw new InvalidArgumentException("Unsupported gateway [$gateway]")
        };
    }
}