<?php

namespace App\Pipelines\Simulator;

use App\Models\GatewayMerchant;
use Closure;

class VerifyProvidedSecretStage
{
    public function handle(array $data, Closure $next)
    {
        $provided = (string) ($data['secret_key_provided'] ?? '');
        $gateway = (string) ($data['gateway']?? '');
        $merchant = (string) ($data['merchant_id']?? '');

            $exists = GatewayMerchant::where('gateway_name', $gateway)
            ->where('merchant_id', $merchant)
            ->where('secret_key', $provided)
            ->exists();

        abort_if(!$exists, 401);

        return $next($data);
    }
}
