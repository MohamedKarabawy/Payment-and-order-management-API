<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    protected $table = 'pg_order_items';
    protected $primaryKey = 'id';

    protected $fillable = [
        'order_id',
        'item_id',
        'product_name',
        'product_description',
        'product_price',
        'product_quantity',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
