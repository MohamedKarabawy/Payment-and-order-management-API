<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GatewayTransaction extends Model
{
    protected $table = 'sm_gateway_transactions';

    protected $primaryKey = 'id';

    protected $fillable = [
        'merchant_id',
        'transaction_id',
        'amount',
        'currency',
        'status'
    ];
}
