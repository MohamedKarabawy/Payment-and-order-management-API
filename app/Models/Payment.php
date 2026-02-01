<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'pg_payments';

    protected $primaryKey = 'id';

    protected $fillable = [
        'order_id',
        'gateway_name',
        'transaction_id',
        'amount',
        'currency',
        'status'
    ];
}
