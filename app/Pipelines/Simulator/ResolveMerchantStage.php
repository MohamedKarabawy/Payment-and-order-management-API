<?php

namespace App\Pipelines\Simulator;

use App\Models\GatewayMerchant;
use Closure;

class ResolveMerchantStage
{
    public function handle(array $data, Closure $next)
    {
         $merchant = GatewayMerchant::where('merchant_id', $data['merchant_id'])
            ->where('gateway_name', $data['gateway'])
            ->where('environment', $data['environment'])
            ->where('status', 'enabled')
            ->firstOrFail();

        $data['merchant'] = $merchant;

        return $next($data);
    }
}
