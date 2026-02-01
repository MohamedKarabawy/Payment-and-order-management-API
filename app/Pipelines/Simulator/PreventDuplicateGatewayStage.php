<?php

namespace App\Pipelines\Simulator;

use App\Models\GatewayMerchant;
use Closure;

class PreventDuplicateGatewayStage
{
    public function handle(array $data, Closure $next)
    {
        $exists = GatewayMerchant::where('gateway_name', $data['gateway'])
            ->where('environment', $data['environment'])
            ->exists();

        abort_if($exists, 409);

        return $next($data);
    }
}
