<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GatewayMerchant extends Model
{
    protected $table = 'sm_gateway_merchants';
    
    protected $primaryKey = 'id';

    protected $fillable = [
        'gateway_name',
        'merchant_id',
        'api_key',
        'secret_key',
        'environment',
        'status'
    ];

    public static function generate(string $gateway, string $environment): self
    {
        return self::create([
            'gateway_name' => $gateway,
            'merchant_id' => $gateway . '_' . Str::lower(Str::random(12)),
            'api_key' => Str::random(48),
            'secret_key' => bin2hex(random_bytes(32)),
            'environment' => $environment,
            'status' => 'enabled'
        ]);
    }
}
