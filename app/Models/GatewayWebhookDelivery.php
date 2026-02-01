<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GatewayWebhookDelivery extends Model
{
    protected $table = 'sm_gateway_webhook_deliveries';

    protected $primaryKey = 'id';

    protected $fillable = [
        'merchant_id',
        'transaction_id',
        'event_type',
        'payload',
        'target_url',
        'signature',
        'status',
        'attempts',
        'last_attempted_at',
        'delivered_at'
    ];

    protected $casts = [
        'payload' => 'array',
        'last_attempted_at' => 'datetime',
        'delivered_at' => 'datetime'
    ];
}
