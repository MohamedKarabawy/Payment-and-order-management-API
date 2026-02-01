<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $table = 'pg_items';
    protected $primaryKey = 'id';


    protected $fillable = [
        'product_name',
        'product_description',
        'product_price',
        'stock_no',
    ];
}
