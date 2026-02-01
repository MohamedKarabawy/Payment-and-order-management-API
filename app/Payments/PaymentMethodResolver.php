<?php

namespace App\Payments;

use App\Contracts\Payments\PaymentMethodInterface;
use App\Payments\PaypalPaymentMethod;
use App\Payments\StripePaymentMethod;
use App\Payments\VisaPaymentMethod;
use InvalidArgumentException;

class PaymentMethodResolver
{
    public function resolve(string $method): PaymentMethodInterface
    {
        return match($method)
        {
            'paypal' => app(PaypalPaymentMethod::class),

            'stripe' => app(StripePaymentMethod::class),

            'visa' => app(VisaPaymentMethod::class),

            default => throw new InvalidArgumentException("Unsupported Payment Method [$method]")
        };
    }
}