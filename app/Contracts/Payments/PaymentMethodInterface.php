<?php

namespace App\Contracts\Payments;

interface PaymentMethodInterface
{
    public function pay(array $payload);
}