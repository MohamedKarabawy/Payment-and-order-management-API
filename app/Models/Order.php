<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'pg_orders';
    protected $primaryKey = 'id';

    protected $fillable = [
        'status',
        'order_no',
        'currency',
        'user_id',
        'customer_name',
        'billing_email',
        'total_price',
        'billing_address',
        'billing_city',
        'billing_country',
        'billing_phone',
        'billing_zip_code',
        'transaction_id',
        'payment_method',
        'payment_status',
    ];

    public function items()
    {
        return $this->belongsToMany(Item::class, 'pg_order_items', 'order_id', 'item_id')
            ->withPivot('product_name', 'product_description', 'product_price', 'product_quantity')
            ->withTimestamps();
    }

    public function attachItems(array $orderItems): void
    {
        $items = Item::whereIn('id', collect($orderItems)->pluck('item_id'))
            ->get(['id', 'product_name', 'product_description', 'product_price']);

        $attachData = $items->mapWithKeys(function ($item) use ($orderItems) {
            $quantity = collect($orderItems)
                ->firstWhere('item_id', $item->id)['quantity'];

            return [
                $item->id => [
                    'product_name' => $item->product_name,
                    'product_description' => $item->product_description,
                    'product_price' => $item->product_price,
                    'product_quantity' => $quantity,
                ]
            ];
        })->toArray();

        $this->items()->sync($attachData);

        $this->setOrderNoAndTotalPrice();
    }

    public function setOrderNoAndTotalPrice(): void
    {
        $this->loadMissing('items');

        $total = $this->items->map(function ($item): float|int {
            return $item->pivot->product_price * $item->pivot->product_quantity;
        })->sum();

        $this->update(['total_price' => $total, 'order_no' => 'PG-' . str_pad($this->id, 8, '0', STR_PAD_LEFT)]);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class, 'order_id');
    }
}
