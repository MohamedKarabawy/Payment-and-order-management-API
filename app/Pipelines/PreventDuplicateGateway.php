<?php

namespace App\Pipelines;

use App\Models\PaymentGateway;
use Closure;

class PreventDuplicateGateway
{
    public function handle(array $gateway, Closure $next)
    {
        $exists = PaymentGateway::where('gateway_name', $gateway['gateway'])
            ->exists();

        abort_if($exists, 409);

        return $next($gateway['gateway']);
    }
}
