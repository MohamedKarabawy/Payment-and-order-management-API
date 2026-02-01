<?php

namespace App\Contracts\Gateways;

use App\Models\PaymentGateway;

interface PaymentGatewayInterface
{
    public function addGateway(array $data): PaymentGateway;

    public function updateGateway(array $data): PaymentGateway;

    public function deleteGateway(): void;
}