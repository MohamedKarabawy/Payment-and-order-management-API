<?php

namespace App\Pipelines\Simulator;

use App\Models\GatewayTransaction;
use Closure;

class IdempotencyStage
{
    public function handle(array $data, Closure $next)
    {
        $key = $data['idempotency_key'] ?? null;

        if ($key) 
        {
            $existing = GatewayTransaction::where('merchant_id', $data['merchant']->merchant_id)
                ->where('idempotency_key', $key)
                ->first();

            if ($existing) 
            {
                $data['existing_transaction'] = $existing;
            }
        }
        
        return $next($data);
    }
}