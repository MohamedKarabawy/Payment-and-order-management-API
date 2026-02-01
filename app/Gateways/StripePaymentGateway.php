<?php

namespace App\Gateways;

use App\Contracts\Gateways\PaymentGatewayInterface;
use App\Models\PaymentGateway;

class StripePaymentGateway implements PaymentGatewayInterface
{
    public function __construct(private string $gateway = 'stripe')
    {}

    public function addGateway(array $data): PaymentGateway
    {
        $data['gateway_name'] = $this->gateway;

        return PaymentGateway::create($data);
    }

    public function updateGateway(array $data): PaymentGateway
    {
        $paymentGateway = PaymentGateway::where('gateway_name', $this->gateway);

        $paymentGateway->update($data);

        return $paymentGateway->first();
    }

    public function deleteGateway(): void
    {
        $paymentGateway = PaymentGateway::where('gateway_name', $this->gateway)->first();

        $paymentGateway->delete();
    }
}