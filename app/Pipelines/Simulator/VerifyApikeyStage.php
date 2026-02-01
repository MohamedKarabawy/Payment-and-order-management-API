<?php

namespace App\Pipelines\Simulator;

use App\Models\GatewayMerchant;
use Closure;

class VerifyApikeyStage
{
    public function handle(array $data, Closure $next)
    {
        $apiKey = (string) ($data['api_key'] ?? '');
        $gateway = (string) ($data['gateway']?? '');
        $merchant = (string) ($data['merchant_id']?? '');

            $exists = GatewayMerchant::where('gateway_name', $gateway)
            ->where('merchant_id', $merchant)
            ->where('api_key', $apiKey)
            ->exists();

        abort_if(!$exists, 401);

        return $next($data);
    }
}
