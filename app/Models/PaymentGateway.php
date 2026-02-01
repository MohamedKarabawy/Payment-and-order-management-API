<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $table = 'pg_payment_gateways';

    protected $primaryKey = 'id';

    protected $fillable = [
        'gateway_name',
        'merchant_id',
        'api_key',
        'secret_key',
        'environment',
        'status'
    ];

}
